<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Facade;

use function get_bloginfo;

/**
 * Class Site
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Site
{

	/**
	 * Gets information of the website.
	 *
	 * @param string $name
	 *
	 * @return string|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @see get_bloginfo()
	 */
	public static function info(string $name): ?string
	{
		return get_bloginfo($name) ?? null;
	}

}
