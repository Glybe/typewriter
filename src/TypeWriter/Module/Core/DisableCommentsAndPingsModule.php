<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\AdminMenu;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function admin_url;
use function wp_redirect;

/**
 * Class DisableCommentsAndPingsModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DisableCommentsAndPingsModule extends Module
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Disables comments and pings on the website.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('admin_init', [$this, 'onAdminInit']);
        Hooks::action('admin_menu', [$this, 'onAdminMenu']);

        Hooks::filter('comments_open', [$this, 'onCommentsOrPingsOpen']);
        Hooks::filter('pings_open', [$this, 'onCommentsOrPingsOpen']);
    }

    /**
     * Invoked on admin_init action hook.
     * Redirects edit-comments.php to admin index.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminInit(): void
    {
        Hooks::removeAction('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);

        global $pagenow;

        if ($pagenow !== 'edit-comments.php')
            return;

        wp_redirect(admin_url());
    }

    /**
     * Invoked on admin_menu action hook.
     * Removes edit-comments.php from admin menu.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminMenu(): void
    {
        AdminMenu::removePage('edit-comments.php');
        AdminMenu::removeSubPage('options-general.php', 'options-discussion.php');
    }

    /**
     * Invoked on comments_open and pings_open filter hook.
     * Disables comments and pings by returning false.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onCommentsOrPingsOpen(): bool
    {
        return false;
    }

}
