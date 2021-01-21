<?php
declare(strict_types=1);

namespace TypeWriter\Error\Runtime;

use const E_USER_NOTICE;

/**
 * Class PhpUserNoticeException
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Runtime
 * @since 1.0.0
 */
final class PhpUserNoticeException extends PhpException
{

    /**
     * PhpUserNoticeException constructor.
     *
     * @param string $message
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $message)
    {
        parent::__construct($message, E_USER_NOTICE);
    }

}
