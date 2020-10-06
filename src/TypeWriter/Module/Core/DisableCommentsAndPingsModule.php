<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use stdClass;
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
     * DisableCommentsAndPingsModule constructor.
     *
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

        Hooks::filter('wp_count_comments', [$this, 'onWpCountComments']);
    }

    /**
     * Invoked on wp_count_comments action hook.
     * Returns zero for everything, we don't want comments.
     *
     * @return stdClass
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onWpCountComments(): stdClass
    {
        $result = new stdClass;

        $result->approved = 0;
        $result->moderated = 0;
        $result->spam = 0;
        $result->trash = 0;
        $result->{'post-trashed'} = 0;
        $result->total_comments = 0;
        $result->all = 0;

        return $result;
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

        if ($pagenow !== 'edit-comments.php') {
            return;
        }

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
