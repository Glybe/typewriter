<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter;

use Cappuccino\Cappuccino;
use Columba\Columba;
use Columba\Database\MySQLDatabaseDriver;
use Columba\Preferences;
use Columba\Router\RouterException;
use Columba\Util\Stopwatch;
use TypeWriter\Cappuccino\CappuccinoRenderer;
use TypeWriter\Module\Module;
use TypeWriter\Module\WP\PostTemplatesLoaderModule;
use TypeWriter\Router\Router;
use TypeWriter\Storage\KeyValueStorage;

/**
 * Class TypeWriter
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter
 * @since 1.0.0
 */
final class TypeWriter
{

	public const VERSION = '1.0.0';

	/**
	 * @var CappuccinoRenderer
	 */
	private $cappuccino;

	/**
	 * @var MySQLDatabaseDriver
	 */
	private $database;

	/**
	 * @var Module[]
	 */
	private $modules;

	/**
	 * @var Preferences
	 */
	private $preferences;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @var KeyValueStorage
	 */
	private $state;

	/**
	 * TypeWriter constructor.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		Stopwatch::start(self::class);

		$this->modules = [];
		$this->preferences = Preferences::loadFromJson(ROOT . '/config/config.json');
		$this->state = new KeyValueStorage();
	}

	/**
	 * Initializes TypeWriter.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function initialize(): void
	{
		$this->cappuccino = new CappuccinoRenderer();
		$this->router = new Router();

		$this->loadModule(PostTemplatesLoaderModule::class);

		$this->state['tw.is-initialized'] = true;
	}

	/**
	 * Runs everything. First checks if we can use router instead of WP stuff.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function run(): void
	{
		require_once(WP_DIR . '/wp-load.php');

		wp();

		$this->state['tw.is-wp-initialized'] = true;
		$this->state['tw.is-wp-used'] = false;

		foreach ($this->modules as $module)
			$module->onRun();

		try
		{
			$this->router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
		}
		catch (RouterException $err)
		{
			if ($err->getCode() !== RouterException::ERR_NOT_FOUND)
				throw $err;

			$this->state['tw.is-wp-used'] = true;

			require_once(WP_DIR . '/wp-includes/template-loader.php');
		}
	}

	/**
	 * Gets the Cappuccino renderer.
	 *
	 * @return CappuccinoRenderer
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getCappuccino(): CappuccinoRenderer
	{
		return $this->cappuccino;
	}

	/**
	 * Gets the database connection instance.
	 *
	 * @return MySQLDatabaseDriver|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getDatabase(): ?MySQLDatabaseDriver
	{
		return $this->database;
	}

	/**
	 * Gets a module by class name.
	 *
	 * @param string $className
	 *
	 * @return Module|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getModule(string $className): ?Module
	{
		foreach ($this->modules as $module)
			if ($module instanceof $className)
				return $module;

		return null;
	}

	/**
	 * Gets all registered modules.
	 *
	 * @return Module[]
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getModules(): array
	{
		return $this->modules;
	}

	/**
	 * Gets the loaded preferences.
	 *
	 * @return Preferences
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function getPreferences(): Preferences
	{
		return $this->preferences;
	}

	/**
	 * Gets the router.
	 *
	 * @return Router
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function getRouter(): Router
	{
		return $this->router;
	}

	/**
	 * Gets the state storage.
	 *
	 * @return KeyValueStorage
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getState(): KeyValueStorage
	{
		return $this->state;
	}

	/**
	 * Gets software versions of TypeWriter.
	 *
	 * @return array
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function getVersions(): array
	{
		global $wp_version;

		return [
			'cappuccino' => Cappuccino::VERSION,
			'columba' => Columba::VERSION,
			'php' => phpversion(),
			'typewriter' => self::VERSION,
			'wordpress' => $wp_version
		];
	}

	/**
	 * Returns TRUE if we're in wp-admin.
	 *
	 * @return bool
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function isAdmin(): bool
	{
		return is_admin();
	}

	/**
	 * Returns TRUE if we're on an API endpoint.
	 *
	 * @return bool
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function isApi(): bool
	{
		return false;
	}

	/**
	 * Returns TRUE if we're on frontend.
	 *
	 * @return bool
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function isFront(): bool
	{
		return !$this->isAdmin() && !$this->isInstalling() && !$this->isLogin();
	}

	/**
	 * Returns TRUE if we're in the wordpress installer.
	 *
	 * @return bool
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function isInstalling(): bool
	{
		return defined('WP_INSTALLING') && WP_INSTALLING;
	}

	/**
	 * Returns TRUE if we're on wp-login.php.
	 *
	 * @return bool
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function isLogin(): bool
	{
		global $pagenow;

		return $pagenow === 'wp-login.php';
	}

	/**
	 * Loads a module.
	 *
	 * @param string $className
	 * @param mixed  ...$args
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function loadModule(string $className, ...$args): void
	{
		$this->modules[] = new $className(...$args);
	}

	/**
	 * Invoked when WordPress is ready to do it's stuff.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function onWordPressLoaded(): void
	{
		foreach ($this->modules as $module)
			$module->onInitialize();
	}

	/**
	 * Sets the database connection instance.
	 *
	 * @param MySQLDatabaseDriver $database
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function setDatabase(MySQLDatabaseDriver $database): void
	{
		$this->database = $database;
	}

}
