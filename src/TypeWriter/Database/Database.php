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

namespace TypeWriter\Database;

use wpdb;

/**
 * Class Database
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Database
 * @since 1.0.0
 */
final class Database extends wpdb
{

	// TODO(Bas): Create a shim for Columba Database stuff that works as a replacement for wpdb.

}
