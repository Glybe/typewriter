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

use Columba\Autoloader;
use Columba\Error\ExceptionHandler;

define('TW_ROOT', realpath(__DIR__ . '/../..'));

const ROOT = TW_ROOT;
const PUBLIC_DIR = ROOT . '/public';
const SRC_DIR = ROOT . '/src';
const VENDOR_DIR = ROOT . '/vendor';
const WP_DIR = PUBLIC_DIR . '/wp';

require_once ROOT . '/vendor/Columba/src/Columba/Autoloader.php';

/**
 * Gets the Autoloader.
 *
 * @return Autoloader
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function autoloader(): Autoloader
{
	static $autoloader = null;

	return $autoloader ??= new Autoloader();
}

/**
 * Gets TypeWriter.
 *
 * @return TypeWriter
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function tw(): TypeWriter
{
	static $tw = null;

	return $tw ?? $tw = new TypeWriter();
}

autoloader()->addDirectory(VENDOR_DIR . '/Cappuccino/src', 'Cappuccino\\');
autoloader()->addDirectory(VENDOR_DIR . '/Columba/src', 'Columba\\');
autoloader()->addDirectory(SRC_DIR);
autoloader()->register();

ExceptionHandler::register();

tw()->initialize();
