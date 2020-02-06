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

namespace TypeWriter\Util;

use function extract;
use function ob_get_clean;
use function ob_start;
use const EXTR_OVERWRITE;

/**
 * Class Sandbox
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Util
 * @since 1.0.0
 */
final class Sandbox
{

	/**
	 * Renders the given php file and returns it's result.
	 *
	 * @param string $file
	 * @param array  $context
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function render(string $file, array $context = []): string
	{
		extract($context, EXTR_OVERWRITE);
		ob_start();

		require $file;

		return ob_get_clean();
	}

}
