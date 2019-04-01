<?php
declare(strict_types=1);

use function TypeWriter\tw;

require_once __DIR__ . '/../src/TypeWriter/boot.php';

define('WP_USE_THEMES', true);

$wp_did_header = true;

tw()->run();
