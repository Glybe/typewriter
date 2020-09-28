<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Tree;

use TypeWriter\Util\PostUtil;
use WP_Post;
use function count;
use function get_posts;

/**
 * Class Tree
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Tree
 * @since 1.0.0
 */
class Tree
{

    private static array $subCache = [];

    /**
     * Gets the tree of the given post.
     *
     * @param WP_Post $post
     * @param int $maxDepth
     *
     * @return TreeItem
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function getTree(WP_Post $post, int $maxDepth = -1): TreeItem
    {
        $depth = 0;
        $root = PostUtil::getRootPost($post);

        $tree = new TreeItem($root);
        $tree->addItems(self::build($root, $depth, $maxDepth));

        return $tree;
    }

    /**
     * Builds a post tree from the given post.
     *
     * @param WP_Post $post
     * @param int $depth
     * @param int $maxDepth
     *
     * @return TreeItem[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private static function build(WP_Post $post, int &$depth = 0, int $maxDepth = -1): array
    {
        if ($maxDepth > 0 && $depth > $maxDepth)
            return [];

        $depth++;

        $items = self::$subCache[$post->ID] ??= get_posts([
            'numberposts' => -1,
            'order' => 'asc',
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
            'post_parent' => $post->ID,
            'post_type' => $post->post_type
        ]);

        if (count($items) === 0)
            return [];

        $tree = [];

        foreach ($items as $item) {
            $treeItem = new TreeItem($item);
            $treeItem->addItems(self::build($item, $depth, $maxDepth));
            $tree[] = $treeItem;
        }

        return $tree;
    }

}
