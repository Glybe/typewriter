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

use Columba\Database\Connection\Connection;
use Columba\Foundation\Preferences\Preferences;
use Columba\Foundation\Store;
use Columba\Http\RequestMethod;
use Columba\Router\RouterException;
use Columba\Util\Stopwatch;
use Composer\InstalledVersions;
use TypeWriter\Error\Reporter\ErrorReporter;
use TypeWriter\Error\Reporter\ProductionErrorChannel;
use TypeWriter\Error\Reporter\WhoopsErrorChannel;
use TypeWriter\Error\ViolationException;
use TypeWriter\Facade\Hooks;
use TypeWriter\Feature\Feature;
use TypeWriter\Module\Module;
use TypeWriter\Router\Router;
use TypeWriter\Twig\TwigRenderer;
use function defined;
use function in_array;
use function is_admin;
use function is_file;
use function phpversion;
use function strpos;
use function wp;
use const WP_DEBUG;
use const WP_INSTALLING;

/**
 * Class TypeWriter
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter
 * @since 1.0.0
 */
final class TypeWriter
{

    private Connection $database;
    private ErrorReporter $errorReporter;
    private Preferences $preferences;
    private Router $router;
    private Store $state;
    private TwigRenderer $twig;

    /** @var Feature[] */
    private array $features = [];

    /** @var Module[] */
    private array $modules = [];

    /**
     * TypeWriter constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        Stopwatch::start(self::class);

        $isDevConfig = is_file(ROOT . '/dev/config.json');

        $this->preferences = Preferences::loadFromJson(ROOT . ($isDevConfig ? '/dev/config.json' : '/config.json'));
        $this->state = new Store();

        $this->errorReporter = new ErrorReporter();

        if ($this->isDebugMode()) {
            $this->errorReporter->addChannel(new WhoopsErrorChannel());
        } else {
            $this->errorReporter->addChannel(new ProductionErrorChannel());
        }
    }

    /**
     * Initializes TypeWriter.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function initialize(): void
    {
        $this->twig = new TwigRenderer();
        $this->router = new Router();

        if ($this->isInstalling()) {
            require __DIR__ . '/installer.php';
        }

        $this->state->set('tw.is-wp-initialized', true);
    }

    /**
     * Runs everything. First checks if we can use router instead of WP stuff.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function run(): void
    {
        require_once(WP_DIR . '/wp-load.php');

        Hooks::action('rest_api_init', fn() => $this->runRouter(
            fn() => die
        ));

        wp();

        $this->state->set('tw.is-wp-initialized', true);
        $this->state->set('tw.is-wp-used', false);

        foreach ($this->modules as $module) {
            $module->onRun();
        }

        $this->runRouter(
            fn() => die,
            fn() => require_once WP_DIR . '/wp-includes/template-loader.php'
        );
    }

    /**
     * Gets the database connection instance.
     *
     * @return Connection|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getDatabase(): ?Connection
    {
        return $this->database;
    }

    /**
     * Gets the error reporter.
     *
     * @return ErrorReporter
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getErrorReporter(): ErrorReporter
    {
        return $this->errorReporter;
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
        foreach ($this->modules as $module) {
            if ($module instanceof $className) {
                return $module;
            }
        }

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
     * @author Bas Milius <bas@mili.us>
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
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Gets the state storage.
     *
     * @return Store
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getState(): Store
    {
        return $this->state;
    }

    /**
     * Gets the Twig renderer.
     *
     * @return TwigRenderer
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getTwig(): TwigRenderer
    {
        return $this->twig;
    }

    /**
     * Gets software versions of TypeWriter.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getVersions(): array
    {
        global $wp_version;

        return [
            'columba' => InstalledVersions::getPrettyVersion('basmilius/columba'),
            'php' => phpversion(),
            'raxos_database' => InstalledVersions::getPrettyVersion('raxos/database'),
            'raxos_foundation' => InstalledVersions::getPrettyVersion('raxos/foundation'),
            'raxos_http' => InstalledVersions::getPrettyVersion('raxos/http'),
            'raxos_router' => InstalledVersions::getPrettyVersion('raxos/router'),
            'typewriter' => InstalledVersions::getPrettyVersion('basmilius/typewriter'),
            'twig' => InstalledVersions::getPrettyVersion('twig/twig'),
            'wordpress' => $wp_version
        ];
    }

    /**
     * Returns TRUE if we're in wp-admin.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
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
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function isApi(): bool
    {
        return strpos($_SERVER['REQUEST_URI'], '/api/wp/') !== false;
    }

    /**
     * Returns TRUE when debug mode is enabled.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function isDebugMode(): bool
    {
        return (defined('WP_DEBUG') && WP_DEBUG) || $this->preferences['developer']['debugMode'];
    }

    /**
     * Returns TRUE if we're on frontend.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
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
     * @author Bas Milius <bas@mili.us>
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
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function isLogin(): bool
    {
        return in_array($_SERVER['PHP_SELF'], ['/wp/wp-login.php', '/wp/wp-register.php']);
    }

    /**
     * Loads a {@see Feature}.
     *
     * @param string $className
     * @param mixed ...$args
     *
     * @return Feature
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function loadFeature(string $className, ...$args): Feature
    {
        if (!is_subclass_of($className, Feature::class)) {
            throw new ViolationException(sprintf('%s is not a %s.', $className, Feature::class), ViolationException::ERR_NOT_A_FEATURE);
        }

        /** @var Feature $feature */
        $feature = new $className(...$args);

        $this->features[] = $feature;

        return $feature;
    }

    /**
     * Loads a {@see Feature} from an instance.
     *
     * @param Feature $feature
     *
     * @return Feature
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function loadFeatureInstance(Feature $feature): Feature
    {
        $this->features[] = $feature;

        return $feature;
    }

    /**
     * Loads a {@see Module}.
     *
     * @param string $className
     * @param mixed ...$args
     *
     * @return Module
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function loadModule(string $className, ...$args): Module
    {
        if (!is_subclass_of($className, Module::class)) {
            throw new ViolationException(sprintf('%s is not a %s.', $className, Module::class), ViolationException::ERR_NOT_A_MODULE);
        }

        /** @var Module $module */
        $module = new $className(...$args);

        if ($this->state->get('tw.is-wp-initialized', false)) {
            $module->onInitialize();
        }

        $this->modules[] = $module;

        return $module;
    }

    /**
     * Invoked when WordPress is ready to do it's stuff.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onWordPressLoaded(): void
    {
        if ($this->state->get('tw.is-wp-initialized', false)) {
            return;
        }

        foreach ($this->modules as $module) {
            $module->onInitialize();
        }
    }

    /**
     * Sets the database connection instance.
     *
     * @param Connection $databaseConnection
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function setDatabase(Connection $databaseConnection): void
    {
        $this->database = $databaseConnection;
    }

    /**
     * Runs the {@see Router}. When it's used, the given onUsed callback is executed
     * and otherwise the given onNotUsed callback is executed.
     *
     * @param callable|null $onUsed
     * @param callable|null $onNotUsed
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function runRouter(?callable $onUsed = null, ?callable $onNotUsed = null): void
    {
        $_SERVER['REQUEST_URI'] ??= '/';
        $_SERVER['REQUEST_METHOD'] ??= RequestMethod::GET;

        try {
            $this->router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

            if ($onUsed !== null) {
                $onUsed();
            }
        } catch (RouterException $err) {
            if ($err->getCode() !== $err::ERR_NOT_FOUND) {
                throw $err;
            }

            $this->state->set('tw.is-wp-used', true);

            if ($onNotUsed !== null) {
                $onNotUsed();
            }
        }
    }

}
