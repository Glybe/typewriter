<?php
declare(strict_types=1);

namespace TypeWriter;

use function getenv;
use function is_bool;
use function is_int;

/**
 * Gets an environment variable.
 *
 * @param string $key
 * @param null $default
 *
 * @return array|bool|int|mixed|string|null
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function env(string $key, $default = null)
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
