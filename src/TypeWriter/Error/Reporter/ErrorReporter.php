<?php
declare(strict_types=1);

namespace TypeWriter\Error\Reporter;

use Columba\Router\RouterException;
use JetBrains\PhpStorm\NoReturn;
use Raxos\Foundation\Environment;
use Throwable;
use TypeWriter\Error\Runtime\PhpException;
use function error_reporting;
use function in_array;
use function ini_set;
use function set_exception_handler;
use function TypeWriter\env;
use function TypeWriter\tw;
use const E_ALL;

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
     * Registers the error reporter.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function initialize(): void
    {
        if (env('MODE', 'development') === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            ini_set('html_errors', '0');
        }

        // todo(Bas): Add support for Rollbar and other error reporting tools.
        set_exception_handler([$this, 'onException']);
//        set_error_handler([$this, 'onError']); // note: Disabled for now.
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
        if (Environment::isCommandLineInterface()) {
            return false;
        }

        if (tw()->isInstalling()) {
            // note: Disable error to exception promoting when installing wordpress, because
            //  the installer may throw warnings that we don't care about.
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
    #[NoReturn]
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
