<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Error;

use Raxos\Foundation\Error\RaxosException;
use Throwable;

/**
 * Class TypeWriterException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error
 * @since 1.0.0
 */
class TypeWriterException extends RaxosException
{

    public const ERR_UNKNOWN = 0;

    /**
     * TypeWriterException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $message = '', int $code = self::ERR_UNKNOWN, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
