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

namespace TypeWriter;

use Composer\Autoload\ClassLoader;

define('TW_ROOT', realpath(__DIR__ . '/../..'));

const ROOT = TW_ROOT;
const PUBLIC_DIR = ROOT . '/public';
const UPLOADS_DIR = PUBLIC_DIR . '/app/uploads';
const RESOURCE_DIR = ROOT . '/resource';
const SRC_DIR = ROOT . '/src';
const VENDOR_DIR = ROOT . '/vendor';
const WP_DIR = PUBLIC_DIR . '/wp';

/**
 * Gets the Autoloader instance.
 *
 * @return ClassLoader
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function autoloader(): ClassLoader
{
    static $autoloader = null;

    return $autoloader ??= require_once ROOT . '/vendor/autoload.php';
}

/**
 * Gets TypeWriter.
 *
 * @return TypeWriter
 * @author Bas Milius <bas@mili.us>
 * @since 1.0.0
 */
function tw(): TypeWriter
{
    static $tw = null;

    return $tw ??= new TypeWriter();
}

autoloader();
tw()->initialize();
