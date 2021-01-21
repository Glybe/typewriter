<?php
declare(strict_types=1);

namespace TypeWriter\Error\Runtime;

use const E_RECOVERABLE_ERROR;

/**
 * Class PhpRecoverableErrorException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Runtime
 * @since 1.0.0
 */
final class PhpRecoverableErrorException extends PhpException
{

    /**
     * PhpRecoverableErrorException constructor.
     *
     * @param string $message
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $message)
    {
        parent::__construct($message, E_RECOVERABLE_ERROR);
    }

}
