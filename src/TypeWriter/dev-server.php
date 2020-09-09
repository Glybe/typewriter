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

ob_start('ob_gzhandler');

if ($requestFile && !$isPhpFile && is_file($requestFile))
{
	$extension = pathinfo($requestFile, PATHINFO_EXTENSION);
	$mimeTypes = [
		'css' => 'text/css',
		'js' => 'application/javascript',
		'json' => 'application/json',

		'gif' => 'image/gif',
		'jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'svg' => 'image/svg+xml',
		'png' => 'image/png',
		'webp' => 'image/webp',

		'eot' => 'application/vnd.ms-fontobject',
		'otf' => 'font/otf',
		'ttf' => 'font/ttf',
		'woff' => 'font/woff',
		'woff2' => 'font/woff2'
	];

	if (isset($mimeTypes[$extension]))
	{
		header('Cache-Control: public, max-age=5184000');
		header('Content-Type: ' . $mimeTypes[$extension] ?? mime_content_type($requestFile));
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime('+ 1 month')));
		readfile($requestFile);
		return true;
	}

	return false;
}

if ($requestFile && is_dir($requestFile) && is_file($requestFile . '/index.php'))
	require_once $requestFile . '/index.php';
else if ($isPhpFile)
	require_once $requestFile;
else
	require_once __DIR__ . '/../../public/index.php';
