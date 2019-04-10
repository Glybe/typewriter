<?php
declare(strict_types=1);

namespace TypeWriter\Module\WP;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
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
		Hooks::filter('admin_footer_text', [$this, 'onAdminFooterText']);
		Hooks::filter('update_footer', [$this, 'onUpdateFooter'], 11);
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

}
