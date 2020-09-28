<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Menu;

use Columba\Facade\IsCountable;
use Columba\Util\ArrayUtil;
use function count;

/**
 * Class MenuObject
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Menu
 * @since 1.0.0
 */
abstract class MenuObject implements IsCountable
{

    protected array $items = [];

    /**
     * Adds a new menu item.
     *
     * @param MenuItem $item
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function addItem(MenuItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Gets an item by the given item id.
     *
     * @param int $itemId
     *
     * @return MenuItem|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getItem(int $itemId): ?MenuItem
    {
        return ArrayUtil::first($this->items, fn(MenuItem $item) => $item->getId() === $itemId);
    }

    /**
     * Gets an item by the given post id.
     *
     * @param int $postId
     *
     * @return MenuItem|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getItemByPost(int $postId): ?MenuItem
    {
        return ArrayUtil::first($this->items, fn(MenuItem $item) => $item->getPostId() === $postId);
    }

    /**
     * Gets all items in the menu object.
     *
     * @return MenuItem[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getItems(): array
    {
        return $this->items;
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
