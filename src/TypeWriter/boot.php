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
use Columba\Util\ArrayUtil;

const ROOT = __DIR__ . '/../..';
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

	return $autoloader ?? $autoloader = new Autoloader();
}

/**
 * print_r wrapped with <pre>.
 *
 * @param mixed ...$data
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function pre(...$data): void
{
	if (count($data) === 1 && is_array($data[0]) && ArrayUtil::isSequentialArray($data[0]))
		$data = $data[0];

	echo sprintf('<pre>%s</pre>', print_r($data, true));
}

/**
 * print_r wrapped with <pre> and dies after.
 *
 * @param mixed ...$data
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function preDie(...$data): void
{
	pre(...$data);
	die;
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
