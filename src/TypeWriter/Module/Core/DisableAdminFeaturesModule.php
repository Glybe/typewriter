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

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Customize_Manager;

/**
 * Class DisableAdminFeaturesModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DisableAdminFeaturesModule extends Module
{

    /**
     * DisableAdminFeaturesModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Disables admin features in WordPress.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('customize_register', [$this, 'onWordPressCustomizeRegister'], 11);
        Hooks::action('wp_dashboard_setup', [$this, 'onWordPressDashboardSetup']);
    }

    /**
     * Invoked on customize_register action hook.
     * Removes the custom css option from the customizer.
     *
     * @param WP_Customize_Manager $customizer
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onWordPressCustomizeRegister(WP_Customize_Manager $customizer): void
    {
        $customizer->remove_control('custom_css');
        $customizer->remove_section('custom_css');
    }

    /**
     * Invoked on wp_dashboard_setup action hook.
     * Removes all obsolete dashboard widgets.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onWordPressDashboardSetup(): void
    {
        global $wp_meta_boxes;

        Hooks::removeAction('welcome_panel', 'wp_welcome_panel');

        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    }

}
