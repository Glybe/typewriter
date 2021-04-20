<?php
declare(strict_types=1);

namespace TypeWriter\Error\Runtime;

use RuntimeException;
use const E_DEPRECATED;
use const E_NOTICE;
use const E_RECOVERABLE_ERROR;
use const E_STRICT;
use const E_USER_DEPRECATED;
use const E_USER_ERROR;
use const E_USER_NOTICE;
use const E_USER_WARNING;
use const E_WARNING;

/**
 * Class PhpException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Runtime
 * @since 1.0.0
 */
abstract class PhpException extends RuntimeException
{

    /**
     * Creates an exception from the given code with the given message.
     *
     * @param int $code
     * @param string $message
     *
     * @return RuntimeException
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function createFromCode(int $code, string $message): RuntimeException
    {
        return match ($code) {
            E_DEPRECATED => new PhpDeprecatedException($message),
            E_NOTICE => new PhpNoticeException($message),
            E_RECOVERABLE_ERROR => new PhpRecoverableErrorException($message),
            E_STRICT => new PhpStrictException($message),
            E_USER_DEPRECATED => new PhpUserDeprecatedException($message),
            E_USER_ERROR => new PhpUserErrorException($message),
            E_USER_NOTICE => new PhpUserNoticeException($message),
            E_USER_WARNING => new PhpUserWarningException($message),
            E_WARNING => new PhpWarningException($message),
            default => new RuntimeException($message, $code),
        };
    }

}
