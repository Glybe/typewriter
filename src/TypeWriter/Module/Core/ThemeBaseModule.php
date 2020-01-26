<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function add_theme_support;

/**
 * Class ThemeBaseModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class ThemeBaseModule extends Module
{

	/**
	 * ThemeBaseModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Basic setup for themes.');
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function onInitialize(): void
	{
		Hooks::action('after_setup_theme', [$this, 'onWordPressAfterSetupTheme']);
	}

	/**
	 * Invoked on the after_setup_theme action hook.
	 * Adds various theme supports values.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressAfterSetupTheme(): void
	{
		add_theme_support('post-thumbnails');
	}

}
