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
 * WordPress install drop-in.
 * Runs after WordPress is installed.
 */

/**
 * Creates the homepage.
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function createHomepage(): void
{
	$postId = wp_insert_post([
		'post_content' => 'Welcome to TypeWriter!',
		'post_status' => 'publish',
		'post_title' => 'Home',
		'post_type' => 'page'
	]);

	update_post_meta($postId, '_wp_page_template', 'template/page/front-page.php');

	update_option('page_on_front', $postId);
	update_option('show_on_front', 'page');
}

/**
 * Removes all default posts and meta.
 *
 * @param wpdb $wpdb
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function truncatePosts(wpdb $wpdb): void
{
	$wpdb->query(sprintf('TRUNCATE TABLE %s', $wpdb->posts));
	$wpdb->query(sprintf('TRUNCATE TABLE %s', $wpdb->postmeta));
}

/**
 * Update various options.
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function updateOptions(): void
{
	update_option('date_format', 'j F Y');
	update_option('time_format', 'H:i');
	update_option('timezone_string', 'Europe/Amsterdam');

	update_option('blogdescription', '');
	update_option('permalink_structure', '/%year%/%monthnum%/%postname%/');

	update_option('default_comment_status', 'closed');
	update_option('default_ping_status', 'closed');

	update_option('large_size_h', 1920);
	update_option('large_size_w', 1920);
	update_option('medium_size_h', 960);
	update_option('medium_size_w', 960);
}

/**
 * Install drop-in.
 *
 * @param int $userId
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @since 1.0.0
 */
function wp_install_defaults(int $userId): void
{
	global $wpdb;

	if (is_blog_installed())
		return;

	updateOptions();
	truncatePosts($wpdb);
	createHomepage();

	update_user_meta($userId, 'show_admin_bar_front', 'false');
}
