<?php
declare(strict_types=1);

namespace TypeWriter;

use Raxos\Database\Connection\Connection;
use Raxos\Foundation\Util\Singleton;
use Raxos\Http\HttpRequest;
use TypeWriter\Twig\Twig;
use function getenv;
use function is_bool;
use function is_int;

/**
 * Gets the database instance.
 *
 * @return Connection
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function db(): Connection
{
    return tw()->getDatabase();
}

/**
 * Gets an environment variable.
 *
 * @param string $key
 * @param null $default
 *
 * @return string|array|bool|int|null
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function env(string $key, $default = null): string|array|bool|int|null
{
    $result = getenv($key) ?: $default;

    if (is_int($default)) {
        return (int)$result;
    }

    if (is_bool($default)) {
        return $result === '1';
    }

    return $result;
}

/**
 * Gets the http request instance.
 *
 * @return HttpRequest
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function request(): HttpRequest
{
    return singleton(HttpRequest::class);
}

/**
 * Returns an instance of the given class and makes sure there is
 * only one instance of it.
 *
 * @template T
 *
 * @param class-string<T> $class
 * @param array $parameters
 *
 * @return T
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function singleton(string $class, array $parameters = []): object
{
    if (Singleton::has($class)) {
        return Singleton::get($class);
    }

    return Singleton::make($class, $parameters);
}

/**
 * Gets the Twig renderer instance.
 *
 * @return Twig
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function twig(): Twig
{
    return singleton(Twig::class);
}
