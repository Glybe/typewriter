<?php
/**
 * Copyright (c) 2019 - IdeeMedia <info@ideemedia.nl>
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
 */

use TypeWriter\Database\Database;
use function TypeWriter\tw;

tw()->onWordPressLoaded();

$wpdb = new Database(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
