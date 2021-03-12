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

use function TypeWriter\env;
use function TypeWriter\tw;
use const TypeWriter\PUBLIC_DIR;

require_once __DIR__ . '/../src/TypeWriter/boot.php';

$isHttps = ($_SERVER['HTTPS'] ?? 'off') === 'on';
$baseUrl = env('BASE_URL', ($isHttps ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '127.0.0.1'));

define('WP_CONTENT_DIR', PUBLIC_DIR . '/app');
define('WP_CONTENT_URL', $baseUrl . '/app');

define('DB_HOST', env('DB_HOST', '127.0.0.1'));
define('DB_NAME', env('DB_NAME', 'typewriter'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', ''));
define('DB_PORT', (int)env('DB_PORT', 3306));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

$table_prefix = env('DB_PREFIX', 'wp_');

define('WP_HOME', $baseUrl);
define('WP_SITEURL', WP_HOME . '/wp');

define('AUTH_KEY', env('KEY_AUTH'));
define('SECURE_AUTH_KEY', env('KEY_AUTH_SECURE'));
define('LOGGED_IN_KEY', env('KEY_LOGGED_IN'));
define('NONCE_KEY', env('KEY_NONCE'));
define('AUTH_SALT', env('SAlT_AUTH'));
define('SECURE_AUTH_SALT', env('SAlT_AUTH_SECURE'));
define('LOGGED_IN_SALT', env('SAlT_LOGGED_IN'));
define('NONCE_SALT', env('SAlT_NONCE'));

define('WP_DEFAULT_THEME', 'base-theme');

define('AUTOMATIC_UPDATER_DISABLED', true);
define('AUTOSAVE_INTERVAL', 300);
define('DISABLE_WP_CRON', true);
define('DISALLOW_FILE_EDIT', true);
define('WP_POST_REVISIONS', false);

define('WP_DEBUG', env('MODE', 'development') === 'development');
define('WP_DEBUG_DISPLAY', WP_DEBUG);

define('WPMU_PLUGIN_DIR', PUBLIC_DIR . '/tw/must-use');
define('WPMU_PLUGIN_URL', '/tw/must-use');

/*
 * Change WordPress cookie names to something else.
 */
define('USER_COOKIE', 'tw_u');
define('PASS_COOKIE', 'tw_p');
define('AUTH_COOKIE', 'tw_a');
define('SECURE_AUTH_COOKIE', 'tw_sa');
define('LOGGED_IN_COOKIE', 'tw_l');
define('TEST_COOKIE', 'tw_t');
define('RECOVERY_MODE_COOKIE', 'tw_r');

require_once __DIR__ . '/wp/wp-settings.php';

tw()->onWordPressLoaded();
