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

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Structure\Menu\Menus;

Hooks::action('init', function (): void {
    Menus::registerLocation('main-menu', 'Hoofdmenu');
});

Hooks::action('wp_enqueue_scripts', function (): void {
    Dependencies::enqueueStyle('app:vendor', Dependencies::themeUri('dist/vendor.css'));
    Dependencies::enqueueStyle('app:theme', Dependencies::themeUri('dist/theme.css'));
    Dependencies::enqueueScript('app:vendor', Dependencies::themeUri('dist/vendor.js'));
    Dependencies::enqueueScript('app:theme', Dependencies::themeUri('dist/theme.js'));
});

register_block_style('tw/structure-column', [
    'name' => 'test',
    'label' => 'Test style'
]);
