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

use const TypeWriter\PUBLIC_DIR;
use function TypeWriter\tw;

require_once __DIR__ . '/../src/TypeWriter/boot.php';

$isHttps = $_SERVER['HTTPS'] === 'on';
$prefs = tw()->getPreferences();

define('WP_CONTENT_DIR', PUBLIC_DIR . '/app');
define('WP_CONTENT_URL', '/app');

define('DB_HOST', $prefs['db']['host']);
define('DB_NAME', $prefs['db']['name']);
define('DB_USER', $prefs['db']['user']);
define('DB_PASSWORD', $prefs['db']['pass']);
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

$table_prefix = $prefs['db']['prefix'] ?? 'wp_';

define('WP_HOME', ($isHttps ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', WP_HOME . '/wp');

define('AUTH_KEY', $prefs['key']['auth']);
define('SECURE_AUTH_KEY', $prefs['key']['auth_secure']);
define('LOGGED_IN_KEY', $prefs['key']['logged_in']);
define('NONCE_KEY', $prefs['key']['nonce']);
define('AUTH_SALT', $prefs['salt']['auth']);
define('SECURE_AUTH_SALT', $prefs['salt']['auth_secure']);
define('LOGGED_IN_SALT', $prefs['salt']['logged_in']);
define('NONCE_SALT', $prefs['salt']['nonce']);

define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', true);
define('DISALLOW_FILE_EDIT', true);

require_once __DIR__ . '/wp/wp-settings.php';

tw()->onWordPressLoaded();
