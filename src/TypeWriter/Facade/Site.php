<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Facade;

use function __;
use function function_exists;
use function get_bloginfo;
use function hash;
use function home_url;
use function pll_current_language;
use function pll_home_url;
use function pll_register_string;
use function pll_translate_string;
use function rtrim;
use function sprintf;

/**
 * Class Site
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Site
{

    /**
     * Gets information of the website.
     *
     * @param string $name
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see get_bloginfo()
     */
    public static function info(string $name): ?string
    {
        return get_bloginfo($name) ?? null;
    }

    /**
     * Registers the given string, if it's needed by the translation plugin.
     *
     * @param string $group
     * @param string $str
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function registerString(string $group, string $str): void
    {
        if (function_exists('pll_register_string')) {
            $id = hash('crc32', $str);
            pll_register_string($id, $str, $group);
        }
    }

    /**
     * Translates the given string with the given params.
     *
     * @param string $str
     * @param array $params
     * @param string|null $language
     * @param string $domain
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function translate(string $str, array $params = [], ?string $language = null, string $domain = 'domain'): string
    {
        if (function_exists('pll_translate_string')) {
            $str = pll_translate_string($str, $language ?? pll_current_language());
        } else {
            $str = __($str, $domain);
        }

        return sprintf($str, ...$params);
    }

    /**
     * Gets the full absolute url for the given path.
     *
     * @param string $path
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function url(string $path = '/'): string
    {
        if (function_exists('pll_home_url')) {
            return rtrim(pll_home_url(), '/') . $path;
        }

        return home_url($path);
    }

}
