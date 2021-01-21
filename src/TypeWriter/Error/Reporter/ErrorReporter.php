<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use Columba\Foundation\System;
use Columba\Router\RouterException;
use Throwable;
use TypeWriter\Error\Runtime\PhpException;
use function in_array;
use function set_error_handler;
use function set_exception_handler;

/**
 * Class ErrorReporter
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Error\Reporter
 * @since 1.0.0
 */
final class ErrorReporter
{

    /** @var ErrorChannel[] */
    private array $channels = [];
    private ?array $context = null;

    /**
     * ErrorReporter constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        // todo(Bas): Add support for Rollbar and other error reporting tools.
        set_exception_handler([$this, 'onException']);
        set_error_handler([$this, 'onError']);
    }

    /**
     * Adds the given channel to the error reporter instance.
     *
     * @param ErrorChannel $channel
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addChannel(ErrorChannel $channel): void
    {
        $this->channels[] = $channel;
    }

    /**
     * Invoked on a non-exception error.
     *
     * @param int $code
     * @param string $message
     * @param string $fileName
     * @param int $line
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onError(int $code, string $message, string $fileName, int $line): bool
    {
        if (System::isCLI()) {
            return false;
        }

        $this->context = [
            'File name' => $fileName,
            'Line Number' => $line
        ];

        throw PhpException::createFromCode($code, $message);
    }

    /**
     * Invoked on any exception that is not handled.
     *
     * @param Throwable $err
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onException(Throwable $err): void
    {
        $skipRouteExceptions = [
            RouterException::ERR_MIDDLEWARE_THREW_EXCEPTION,
            RouterException::ERR_RENDERER_THREW_EXCEPTION,
            RouterException::ERR_ROUTE_THREW_EXCEPTION
        ];

        if ($err instanceof RouterException && in_array($err->getCode(), $skipRouteExceptions)) {
            $err = $err->getPrevious();
        }

        foreach ($this->channels as $channel) {
            $channel->onException($err, $this->context);
        }

        $this->context = null;

        die;
    }

}
