<?php
declare(strict_types=1);

namespace TypeWriter\Module\WP;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function TypeWriter\preDie;

/**
 * Class DisableWPFeaturesModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\WP
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
		Hooks::action('wp_head', [$this, 'onWordPressHeader'], 0);
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
	 * Invoked on wp_footer action hook.
	 * Removes obsolete dependencies.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressFooter(): void
	{
		Dependencies::deregisterScript('wp-embed');
	}

	/**
	 * Invoked on wp_head action hook.
	 * Removes obsolete dependencies.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressHeader(): void
	{
		Dependencies::deregisterStyle('wp-block-library');
	}

}
