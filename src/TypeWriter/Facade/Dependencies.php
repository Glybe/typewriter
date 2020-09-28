<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use function get_theme_file_path;
use function get_theme_file_uri;
use function wp_dequeue_script;
use function wp_dequeue_style;
use function wp_deregister_script;
use function wp_deregister_style;
use function wp_enqueue_script;
use function wp_enqueue_style;
use function wp_register_script;
use function wp_register_style;

/**
 * Class Dependencies
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Dependencies
{

    public const ALL_FONT_STYLES = ['100', '100i', '200', '200i', '300', '300i', '400', '400i', '500', '500i', '600', '600i', '700', '700i', '800', '800i', '900', '900i'];

    /**
     * Dequeues a script dependency.
     *
     * @param string $handle
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function dequeueScript(string $handle): void
    {
        wp_dequeue_script($handle);
    }

    /**
     * Dequeues a stylesheet dependency.
     *
     * @param string $handle
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function dequeueStyle(string $handle): void
    {
        wp_dequeue_style($handle);
    }

    /**
     * Deregisters a script dependency.
     *
     * @param string $handle
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function deregisterScript(string $handle): void
    {
        wp_deregister_script($handle);
    }

    /**
     * Deregisters a stylesheet dependency.
     *
     * @param string $handle
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function deregisterStyle(string $handle): void
    {
        wp_deregister_style($handle);
    }

    /**
     * Enqueues a script dependency.
     *
     * @param string $handle
     * @param string|null $src
     * @param array $dependencies
     * @param string|null $version
     * @param bool $inFooter
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function enqueueScript(string $handle, ?string $src = null, array $dependencies = [], ?string $version = null, bool $inFooter = true): void
    {
        wp_enqueue_script($handle, $src, $dependencies, $version ?? false, $inFooter);
    }

    /**
     * Enqueues a style dependency.
     *
     * @param string $handle
     * @param string|null $src
     * @param array $dependencies
     * @param string|null $version
     * @param string $media
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function enqueueStyle(string $handle, ?string $src = null, array $dependencies = [], ?string $version = null, string $media = 'all'): void
    {
        wp_enqueue_style($handle, $src, $dependencies, $version ?? false, $media);
    }

    /**
     * Registers a script dependency.
     *
     * @param string $handle
     * @param string $src
     * @param array $dependencies
     * @param string|null $version
     * @param bool $inFooter
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function registerScript(string $handle, string $src, array $dependencies = [], ?string $version = null, bool $inFooter = true): void
    {
        wp_register_script($handle, $src, $dependencies, $version ?? false, $inFooter);
    }

    /**
     * Registers a style dependency.
     *
     * @param string $handle
     * @param string $src
     * @param array $dependencies
     * @param string|null $version
     * @param string $media
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function registerStyle(string $handle, string $src, array $dependencies = [], ?string $version = null, string $media = 'all'): void
    {
        wp_register_style($handle, $src, $dependencies, $version ?? false, $media);
    }

    /**
     * Generates a Bm Fonts url.
     *
     * @param string $fontName
     * @param array $fontStyles
     * @param string $fontDisplay
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function bmFontsUrl(string $fontName, array $fontStyles = self::ALL_FONT_STYLES, string $fontDisplay = 'swap'): string
    {
        return sprintf('https://font.mili.us/css?display=%s&family=%s:%s', $fontDisplay, urlencode($fontName), urlencode(implode(',', $fontStyles)));
    }

    /**
     * Generates a Google Fonts url.
     *
     * @param string $fontName
     * @param array $fontStyles
     * @param string $fontDisplay
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function googleFontsUrl(string $fontName, array $fontStyles = self::ALL_FONT_STYLES, string $fontDisplay = 'swap'): string
    {
        return sprintf('https://fonts.googleapis.com/css?display=%s&family=%s:%s', $fontDisplay, urlencode($fontName), urlencode(implode(',', $fontStyles)));
    }

    /**
     * Generates an IdeeMedia Fonts url.
     *
     * @param string $fontName
     * @param array $fontStyles
     * @param string $fontDisplay
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function ideemediaFontsUrl(string $fontName, array $fontStyles = self::ALL_FONT_STYLES, string $fontDisplay = 'swap'): string
    {
        return sprintf('https://font.ideemedia.cloud/css?display=%s&family=%s:%s', $fontDisplay, urlencode($fontName), urlencode(implode(',', $fontStyles)));
    }

    /**
     * Gets the path of the given path in one of the active themes.
     *
     * @param string $path
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function themePath(string $path): string
    {
        return get_theme_file_path($path);
    }

    /**
     * Gets the URL of the given path in one of the active themes.
     *
     * @param string $path
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function themeUri(string $path): string
    {
        return get_theme_file_uri($path);
    }

}
