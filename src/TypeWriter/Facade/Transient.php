<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use function delete_site_transient;
use function delete_transient;
use function get_site_transient;
use function get_transient;
use function json_decode;
use function json_encode;
use function set_site_transient;
use function set_transient;

/**
 * todo(Bas)
 *  When the Redis feature is added, check for it and store transients there. We
 *  should also check for an existing Redis WP plugin and use that.
 */

/**
 * Class Transient
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Transient
{

    public const DEFAULT = 1;
    public const SITE = 2;

    /**
     * Deletes the given transient.
     *
     * @param string $key
     * @param int $mode
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function delete(string $key, int $mode = self::DEFAULT): bool
    {
        return $mode === self::SITE ? delete_site_transient($key) : delete_transient($key);
    }

    /**
     * Gets the value of a transient. Returns NULL when it doesn't exist or the
     * value is empty.
     *
     * @param string $key
     * @param int $mode
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(string $key, int $mode = self::DEFAULT): mixed
    {
        $value = $mode === self::SITE ? get_site_transient($key) : get_transient($key);

        if ($value === false) {
            return null;
        }

        return json_decode($value, true);
    }

    /**
     * Sets or updates the value of a transient.
     *
     * @param string $key
     * @param mixed $value
     * @param int $expiresIn
     * @param int $mode
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function set(string $key, mixed $value, int $expiresIn = 3600, int $mode = self::DEFAULT): bool
    {
        $value = json_encode($value);

        if ($mode === self::SITE) {
            return set_site_transient($key, $value, $expiresIn);
        } else {
            return set_transient($key, $value, $expiresIn);
        }
    }

    /**
     * Remembers the given function for the given time.
     *
     * @param string $key
     * @param int $expiresIn
     * @param callable $fn
     * @param int $mode
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function remember(string $key, int $expiresIn, callable $fn, int $mode = self::DEFAULT): mixed
    {
        $known = self::get($key, $mode);

        if ($known !== null) {
            return $known;
        }

        self::set($key, $value = $fn(), $expiresIn, $mode);

        return $value;
    }

}
