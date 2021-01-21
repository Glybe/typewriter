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
        switch ($code) {
            case E_DEPRECATED:
                return new PhpDeprecatedException($message);

            case E_NOTICE:
                return new PhpNoticeException($message);

            case E_RECOVERABLE_ERROR:
                return new PhpRecoverableErrorException($message);

            case E_STRICT:
                return new PhpStrictException($message);

            case E_USER_DEPRECATED:
                return new PhpUserDeprecatedException($message);

            case E_USER_ERROR:
                return new PhpUserErrorException($message);

            case E_USER_NOTICE:
                return new PhpUserNoticeException($message);

            case E_USER_WARNING:
                return new PhpUserWarningException($message);

            case E_WARNING:
                return new PhpWarningException($message);

            default:
                return new RuntimeException($message, $code);
        }
    }

}
