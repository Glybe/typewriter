<?php
declare(strict_types=1);

namespace TypeWriter\Error\Runtime;

use const E_WARNING;

/**
 * Class PhpWarningException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Runtime
 * @since 1.0.0
 */
final class PhpWarningException extends PhpException
{

    /**
     * PhpWarningException constructor.
     *
     * @param string $message
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $message)
    {
        parent::__construct($message, E_WARNING);
    }

}
