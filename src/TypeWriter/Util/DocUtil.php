<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Util;

/**
 * Class DocUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Util
 * @since 1.0.0
 */
final class DocUtil
{

    /**
     * Parses a template file to template properties.
     *
     * @param string $file
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.2.0
     */
    public static function getProperties(string $file): array
    {
        $properties = [];

        if (!is_file($file))
            return $properties;

        $contents = file_get_contents($file);
        $matches = [];
        $pattern = '#@([a-zA-Z0-9-]+) ([\w\- .,<@>\(\)]+)#';

        preg_match_all($pattern, $contents, $matches);

        $keys = $matches[1];
        $values = $matches[2];

        for ($i = 0, $length = count($keys); $i < $length; $i++)
            $properties[$keys[$i]] = trim($values[$i]);

        return $properties;
    }

}
