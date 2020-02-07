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

namespace TypeWriter\Error;

/**
 * Class WordPressException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error
 * @since 1.0.0
 */
class WordPressException extends TypeWriterException
{

	public const ERR_REGISTER_FAILED = 1;
	public const ERR_DOING_IT_WRONG = 2;

}
