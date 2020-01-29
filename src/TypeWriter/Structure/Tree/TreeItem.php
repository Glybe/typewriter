<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Tree;

use Columba\Facade\ICountable;
use WP_Post;
use function count;

/**
 * Class TreeItem
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Tree
 * @since 1.0.0
 */
class TreeItem implements ICountable
{

	protected array $items = [];
	protected WP_Post $post;

	/**
	 * TreeItem constructor.
	 *
	 * @param WP_Post $post
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(WP_Post $post)
	{
		$this->post = $post;
	}

	/**
	 * Adds the given item as sub item.
	 *
	 * @param TreeItem $item
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function addItem(TreeItem $item): void
	{
		$this->items[] = $item;
	}

	/**
	 * Adds the given items as sub items.
	 *
	 * @param TreeItem[] $items
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function addItems(array $items): void
	{
		foreach ($items as $item)
			$this->items[] = $item;
	}

	/**
	 * Gets the sub items.
	 *
	 * @return TreeItem[]
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * Gets the post instance.
	 *
	 * @return WP_Post
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getPost(): WP_Post
	{
		return $this->post;
	}

	/**
	 * Returns TRUE if the tree item has sub items.
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function hasItems(): bool
	{
		return $this->count() > 0;
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function count(): int
	{
		return count($this->items);
	}

}
