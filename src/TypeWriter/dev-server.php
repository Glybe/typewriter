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
$isPhpFile = str_ends_with($requestFile ?: '', '.php');

ob_start('ob_gzhandler');

if ($requestFile && !$isPhpFile && is_file($requestFile)) {
    $extension = pathinfo($requestFile, PATHINFO_EXTENSION);
    $mimeTypes = [
        'css' => 'text/css',
        'html' => 'text/html',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'text/xml',
        'map' => 'application/json',

        'ico' => 'image/x-icon',
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

    if (isset($mimeTypes[$extension])) {
        header('Access-Control-Allow-Origin: *');
        header('Cache-Control: public, max-age=7776000');
        header('Content-Type: ' . $mimeTypes[$extension] ?? mime_content_type($requestFile));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime('+ 3 months')));
        readfile($requestFile);

        return true;
    }

    return false;
}

if ($requestFile && is_dir($requestFile) && is_file($requestFile . '/index.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once $requestFile . '/index.php';
} else if ($isPhpFile) {
    /** @noinspection PhpIncludeInspection */
    require_once $requestFile;
} else {
    require_once __DIR__ . '/../../public/index.php';
}
