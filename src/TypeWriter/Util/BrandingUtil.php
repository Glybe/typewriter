<?php
declare(strict_types=1);

namespace TypeWriter\Util;

use function array_merge;
use function file_get_contents;
use function is_file;
use function json_decode;
use const TypeWriter\ROOT;

/**
 * Class BrandingUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Util
 * @since 1.0.0
 */
final class BrandingUtil
{

    private const DEFAULTS = [
        'name' => 'TypeWriter',
        'logo' => 'https://cdn.glybe.nl/public/brand/SVG/logo-horizontal.svg',
        'logo_url' => 'https://github.com/glybe/',
        'logo_height' => 39,
        'logo_width' => 125
    ];

    private static ?array $json = null;

    /**
     * Gets a branding configuration key.
     *
     * @param string $key
     * @param string|int|bool|null $defaultValue
     *
     * @return array|string|int|bool|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(string $key, array|string|int|bool|null $defaultValue = null): array|string|int|bool|null
    {
        self::load();

        return self::$json[$key] ?? $defaultValue;
    }

    /**
     * Loads the branding configuration.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private static function load(): void
    {
        if (self::$json !== null) {
            return;
        }

        $fileName = ROOT . '/branding.json';

        if (is_file($fileName)) {
            self::$json = json_decode(file_get_contents($fileName), true);
        } else {
            self::$json = [];
        }

        self::$json = array_merge(self::DEFAULTS, self::$json);
    }

}
