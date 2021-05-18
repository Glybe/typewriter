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

/*
 * WordPress db drop-in.
 * Custom database class.
 *
 * The Database class extends the wpdb class and overrides the
 * essential methods to use Columba MySQLDatabaseDriver. Inside
 * the class the driver will also be assigned to the TypeWriter
 * instance.
 */

use TypeWriter\Database\Database;

if (is_file(__DIR__ . '/hooks.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once __DIR__ . '/hooks.php';
}

$wpdb = new Database(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST, DB_PORT);
