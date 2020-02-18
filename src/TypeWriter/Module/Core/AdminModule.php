<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;

/**
 * Class AdminModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class AdminModule extends Module
{

	/**
	 * AdminModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Adds basic TypeWriter features to the WordPress Admin.');
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function onInitialize(): void
	{
		Hooks::action('admin_enqueue_scripts', [$this, 'onAdminEnqueueScripts']);
		Hooks::action('in_admin_footer', [$this, 'onInAdminFooter']);
	}

	/**
	 * Invoked on admin_enqueue_scripts action hook.
	 * Adds the TypeWriter JS and CSS files to the WordPress Admin.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onAdminEnqueueScripts(): void
	{
		Dependencies::enqueueStyle('tw', home_url('/tw/dist/admin.css'));
		Dependencies::enqueueScript('tw', home_url('/tw/dist/admin.js'));
	}

	/**
	 * Invoked on in_admin_footer action hook.
	 * Adds the TypeWriter feature scripts.
	 *
	 * @hook tw.admin-scripts.body (array $scripts): array
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onInAdminFooter(): void
	{
		$fetchScripts = function(): string
		{
			return implode(PHP_EOL, Hooks::applyFilters('tw.admin-scripts.body', []));
		};

		echo <<<CODE
		<script type="text/javascript">
		window.addEventListener("load", function () { 
		{$fetchScripts()}
		});
		</script>
		CODE;
	}

}
