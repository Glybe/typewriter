<?php
declare(strict_types=1);

/**
 * @author Bas Milius <bas@mili.us>
 * @template Front page
 */

the_post();
get_header();

the_title('<h3>', '</h3>');
the_content();

get_footer();
