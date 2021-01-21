<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use Throwable;

/**
 * Class ErrorChannel
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Reporter
 * @since 1.0.0
 */
abstract class ErrorChannel
{

    /**
     * Executes on an exception.
     *
     * @param Throwable $err
     * @param array|null $context
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public abstract function onException(Throwable $err, ?array $context): void;

}
