<?php
/**
 * Copyright (c) 2019 - IdeeMedia <info@ideemedia.nl>
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
use Columba\Preferences;
use Columba\Util\Stopwatch;
use TypeWriter\Facade\Hooks;

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
	 * @var Preferences
	 */
	private $preferences;

	/**
	 * TypeWriter constructor.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		Stopwatch::start(self::class);

		$this->preferences = Preferences::loadFromJson(ROOT . '/config/config.json');
	}

	/**
	 * Initializes TypeWriter.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function initialize(): void
	{
	}

	/**
	 * Executed when WordPress is loaded.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function onWordPressLoaded(): void
	{
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

}
