<?php
/**
 * Copyright (c) 2019 - IdeeMedia <info@ideemedia.nl>
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Screen;

/**
 * Class Screen
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Screen
 * @since 1.0.0
 */
abstract class Screen
{

	/**
	 * Gets the screen id.
	 *
	 * @return string
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public final function getId(): string
	{
		return get_called_class();
	}

}
