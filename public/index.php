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

use function TypeWriter\tw;

require_once __DIR__ . '/../src/TypeWriter/boot.php';

define('WP_USE_THEMES', true);

tw()->run();
