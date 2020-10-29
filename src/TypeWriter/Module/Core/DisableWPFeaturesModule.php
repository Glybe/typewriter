<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Scripts;
use function array_diff;
use function TypeWriter\tw;

/**
 * Class DisableWPFeaturesModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DisableWPFeaturesModule extends Module
{

    /**
     * DisableWPFeaturesModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Disables WordPress features that we do not need.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('init', [$this, 'onWordPressInit'], 0);
        Hooks::action('wp_default_scripts', [$this, 'onWordPressDefaultScripts']);
        Hooks::action('wp_footer', [$this, 'onWordPressFooter'], 0);
    }

    /**
     * Invoked on init action hook.
     * Removes obsolete WordPress features and features that we'll override.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onWordPressInit(): void
    {
        Hooks::removeAction('wp_head', 'feed_links');
        Hooks::removeAction('wp_head', 'feed_links_extra');
        Hooks::removeAction('wp_head', 'rest_output_link_wp_head');
        Hooks::removeAction('wp_head', 'rsd_link');
        Hooks::removeAction('wp_head', 'wlwmanifest_link');
        Hooks::removeAction('wp_head', 'wp_generator');

        Hooks::removeAction('wp_head', 'print_emoji_detection_script', 7);
        Hooks::removeAction('wp_print_styles', 'print_emoji_styles');
        Hooks::removeAction('admin_print_scripts', 'print_emoji_detection_script');
        Hooks::removeAction('admin_print_styles', 'print_emoji_styles');
    }

    /**
     * Invoked on wp_default_scripts action hook.
     * Removes jQuery Migrate from the jQuery dependency when not in admin mode.
     *
     * @param WP_Scripts $scripts
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onWordPressDefaultScripts(WP_Scripts $scripts): void
    {
        if (tw()->isAdmin() || tw()->isLogin() || !isset($scripts->registered['jquery'])) {
            return;
        }

        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }

    /**
     * Invoked on wp_footer action hook.
     * Removes obsolete dependencies.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onWordPressFooter(): void
    {
        Dependencies::dequeueScript('wp-embed');
    }

}
