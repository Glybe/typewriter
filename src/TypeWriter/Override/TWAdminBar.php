<?php
declare(strict_types=1);

namespace TypeWriter\Override;

use function TypeWriter\tw;
use WP_Admin_Bar;

/**
 * Class TWAdminBar
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Override
 * @since 1.0.0
 */
final class TWAdminBar extends WP_Admin_Bar
{

	public function render()
	{
		$root = $this->_bind();

		echo tw()->getCappuccino()->render('@tw/admin/admin-bar', [
			'children' => $root->children,
			'site_name' => get_bloginfo('name')
		]);
	}

}
