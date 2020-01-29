<?php
declare(strict_types=1);

namespace TypeWriter\Util;

use WP_Post;
use function get_post;

/**
 * Class PostUtil
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Util
 * @since 1.0.0
 */
class PostUtil
{

	private static array $rootCache = [];

	/**
	 * Gets the root post of the given post.
	 *
	 * @param WP_Post $post
	 *
	 * @return WP_Post
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function getRootPost(WP_Post $post): WP_Post
	{
		if (isset(self::$rootCache[$post->ID]))
			return self::$rootCache[$post->ID];

		$current = $post;

		while ($current->post_parent > 0)
			$current = get_post($current->post_parent);

		return self::$rootCache[$post->ID] = $current;
	}

}
