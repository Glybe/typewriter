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

use Tolk\SidebarFeature;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Feature\Relation;
use TypeWriter\Structure\Menu\Menus;
use function TypeWriter\tw;

Hooks::action('init', function (): void {
    Menus::registerLocation('main-menu', 'Hoofdmenu');

    tw()->loadFeature(SidebarFeature::class);
    tw()->loadFeature(Relation::class, 'page', 'sidebar', 'Sidebar', 'tolk-sidebar');
});

Hooks::action('wp_enqueue_scripts', function (): void {
    Dependencies::enqueueStyle('app', Dependencies::themeUri('dist/tolk.css'));
    Dependencies::enqueueScript('app', Dependencies::themeUri('dist/tolk.js'));
});
