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

$_SERVER['REQUEST_SCHEME'] = $_SERVER['REQUEST_SCHEME'] ?? 'http';

$requestPath = explode('?', $_SERVER['REQUEST_URI'])[0];
$requestFile = realpath($_SERVER['DOCUMENT_ROOT'] . $requestPath);
$isPhpFile = substr($requestFile ?: '', -4) === '.php';

if ($requestFile && !$isPhpFile && is_file($requestFile))
	return false;

if ($requestFile && is_dir($requestFile) && is_file($requestFile . '/index.php'))
	require_once $requestFile . '/index.php';
else if ($isPhpFile)
	require_once $requestFile;
else
	require_once __DIR__ . '/../../public/index.php';