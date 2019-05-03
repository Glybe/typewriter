<?php
declare(strict_types=1);

namespace TypeWriter\Module\TW;

use TypeWriter\Facade\AdminMenu;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;

/**
 * Class AdminMenuModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\TW
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
		AdminMenu::addPage('dashicons-edit', 'TypeWriter', AdminMenu::cappuccino('@tw/admin/about'));
		AdminMenu::addSubPage('typewriter', 'Settings', AdminMenu::cappuccino('@tw/admin/settings'));
	}

}
