<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

/*
 * Plugin Name: TypeWriter Mod Modules
 * Description: Loads mods for certain plugins. TypeWriter will change how those plugin interact with it.
 * Author: Bas Milius
 * Author URI: https://bas.dev
 */

declare(strict_types=1);

use TypeWriter\Facade\Plugin;
use TypeWriter\Module\Mod\QueryMonitorSupport;
use TypeWriter\Module\Mod\WPMigrateDbSupport;
use function TypeWriter\tw;

Plugin::exists('query-monitor', fn() => tw()->loadModule(QueryMonitorSupport::class));
Plugin::exists('wp-migrate-db', fn() => tw()->loadModule(WPMigrateDbSupport::class));
