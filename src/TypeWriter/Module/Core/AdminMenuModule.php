<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\AdminMenu;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function __;

/**
 * Class AdminMenuModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class AdminMenuModule extends Module
{

    /**
     * AdminMenuModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Creates the TypeWriter admin menu and various options.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('admin_menu', [$this, 'onAdminMenu']);
    }

    /**
     * Invoked on admin_menu action hook.
     * Adds the TypeWriter menu entry.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminMenu(): void
    {
        AdminMenu::addPage('dashicons-edit', __('TypeWriter', 'tw'), AdminMenu::twig('@tw/admin/about'));
        AdminMenu::addSubPage('typewriter', __('Roles &amp; Permissions', 'tw'), AdminMenu::twig('@tw/admin/roles-and-permissions'));
        AdminMenu::addSubPage('typewriter', __('Settings', 'tw'), AdminMenu::twig('@tw/admin/settings'));
    }

}
