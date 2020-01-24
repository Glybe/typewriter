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

/**
 * @author Bas Milius <bas@mili.us>
 */

the_post();
get_header();

the_title('<h1>', '</h1>');
the_content();

get_footer();
