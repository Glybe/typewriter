<?php
declare(strict_types=1);

namespace TypeWriter\Module\WP;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use TypeWriter\Override\TWAdminBar;
use function TypeWriter\tw;
use TypeWriter\TypeWriter;

/**
 * Class AdminDesignModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\WP
 * @since 1.0.0
 */
final class AdminDesignModule extends Module
{

	/**
	 * AdminDesignModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Enhances the wp-admin design with some modifications.');
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function onInitialize(): void
	{
		Hooks::action('admin_enqueue_scripts', [$this, 'onAdminEnqueueScripts']);
		Hooks::action('admin_footer', 'wp_admin_bar_render');

		Hooks::filter('admin_footer_text', [$this, 'onAdminFooterText']);
		Hooks::filter('update_footer', [$this, 'onUpdateFooter'], 11);
		Hooks::filter('wp_admin_bar_class', [$this, 'onWordPressAdminBarClass']);

		Hooks::removeAction('in_admin_header', 'wp_admin_bar_render', 0);
	}

	/**
	 * Invoked on admin_enqueue_scripts action hook.
	 * Removes a few WordPress default dependencies and adds our own.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onAdminEnqueueScripts(): void
	{
//		Dependencies::deregisterStyle('wp-admin');
		Dependencies::deregisterStyle('ie');
//		Dependencies::deregisterStyle('colors');
//		Dependencies::deregisterStyle('colors-fresh');
//		Dependencies::deregisterStyle('colors-classic');
//		Dependencies::deregisterStyle('media');
		Dependencies::deregisterStyle('install');
		Dependencies::deregisterStyle('thickbox');
		Dependencies::deregisterStyle('farbtastic');
		Dependencies::deregisterStyle('jcrop');
		Dependencies::deregisterStyle('imgareaselect');
		Dependencies::deregisterStyle('admin-bar');
		Dependencies::deregisterStyle('wp-jquery-ui-dialog');
//		Dependencies::deregisterStyle('editor-buttons');
//		Dependencies::deregisterStyle('wp-pointer');
		Dependencies::deregisterStyle('jquery-listfilterizer');
		Dependencies::deregisterStyle('jquery-ui-smoothness');
//		Dependencies::deregisterStyle('tooltips');

		Dependencies::deregisterScript('admin-bar');
//		Dependencies::deregisterScript('common');

		Dependencies::enqueueStyle('mdi', '//unpkg.com/@mdi/font/css/materialdesignicons.min.css');
		Dependencies::enqueueStyle('proxima-nova', '//cdn.mili.us/font/proxima-nova/proxima-nova.min.css');
		Dependencies::enqueueStyle('latte-ui', '//unpkg.com/@bybas/latte-ui/dist/latte.css');
		Dependencies::enqueueScript('vue', '//unpkg.com/vue');
		Dependencies::enqueueScript('latte-ui', '//unpkg.com/@bybas/latte-ui/dist/latte.js');
	}

	/**
	 * Invoked on admin_footer_text filter hook.
	 * Returns custom footer text with the TypeWriter version etc.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onAdminFooterText(): string
	{
		return 'Copyright &copy; Bas Milius &mdash; All rights reserved.';
	}

	/**
	 * Invoked on update_footer filter hook.
	 * Returns the current TypeWriter and WordPress versions.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onUpdateFooter(): string
	{
		return sprintf('TypeWriter %s | WordPress %s', TypeWriter::VERSION, tw()->getVersions()['wordpress']);
	}

	/**
	 * Invoked on wp_admin_bar_class filter hook.
	 * Returns our custom WP_Admin_Bar implementation.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressAdminBarClass(): string
	{
		return TWAdminBar::class;
	}

}
