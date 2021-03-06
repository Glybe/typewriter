<?php
/*
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
 * Class HookException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error
 * @since 1.0.0
 */
final class HookException extends TypeWriterException
{

    public const ERR_INVALID_CALLABLE = 0xAA0010;
    public const ERR_REFLECTION_FAILED = 0xAA0011;

}
