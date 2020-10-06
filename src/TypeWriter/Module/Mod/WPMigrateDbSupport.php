<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Module\Mod;

use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Plugin;
use TypeWriter\Module\Module;
use function array_filter;
use function strpos;

/**
 * Class WPMigrateDbSupport
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Mod
 * @since 1.0.0
 */
final class WPMigrateDbSupport extends Module
{

    /**
     * WPMigrateDbSupport constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adds support for the WP Migrate DB plugin.');
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onInitialize(): void
    {
        if (!Plugin::active('wp-migrate-db/wp-migrate-db.php')) {
            return;
        }

        Hooks::filter('tw.theme-base.directories', [$this, 'onThemeDirectories']);
    }

    /**
     * Invoked on the tw.theme-base.directories filter hook.
     * Removes the potential migrate db temporary theme.
     *
     * @param string[] $directories
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onThemeDirectories(array $directories): array
    {
        return array_filter($directories, fn(string $directory): bool => strpos($directory, 'temp-theme') === false);
    }

}
