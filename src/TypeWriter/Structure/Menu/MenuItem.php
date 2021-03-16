<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Menu;

use TypeWriter\Facade\Template;
use WP_Post;
use function explode;
use function get_post_meta;
use function intval;
use function str_contains;
use function trim;

/**
 * Class MenuItem
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Menu
 * @since 1.0.0
 */
class MenuItem extends MenuObject
{

    protected array $classes;
    protected array $items = [];
    protected Menu $menu;
    protected ?self $parent;
    protected WP_Post $post;

    /**
     * MenuItem constructor.
     *
     * @param Menu $menu
     * @param WP_Post $post
     * @param MenuItem|null $parent
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(Menu $menu, WP_Post $post, ?self $parent = null)
    {
        $this->classes = $post->{'classes'} ?? [];
        $this->menu = $menu;
        $this->parent = $parent;
        $this->post = $post;
    }

    /**
     * Gets the classes of this item.
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Gets the icon, or NULL if there is no icon.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getIcon(): ?string
    {
        $icon = get_post_meta($this->post->ID, 'tw_menu_icon', true) ?: null;

        if ($icon === null || trim($icon) === '' || !str_contains($icon, ' ')) {
            return null;
        }

        [$style, $icon] = explode(' ', $icon, 2);

        return Template::renderIcon($style, $icon);
    }

    /**
     * Gets the ID of the menu item.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getId(): int
    {
        return intval($this->post->ID);
    }

    /**
     * Gets the label of the menu item.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getLabel(): string
    {
        return $this->post->{'title'};
    }

    /**
     * Gets the {@see Menu} where this item is part of.
     *
     * @return Menu
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * Gets the parent {@see MenuItem} or NULL if there is no parent.
     *
     * @return MenuItem|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getParent(): ?MenuItem
    {
        return $this->parent;
    }

    /**
     * Gets the associated post.
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
     * Gets the associated post id.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getPostId(): int
    {
        return intval($this->post->{'object_id'});
    }

    /**
     * Gets the target of the menu item link.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getTarget(): string
    {
        $target = $this->post->{'target'} ?? '_self';

        if (empty($target)) {
            $target = '_self';
        }

        return $target;
    }

    /**
     * Gets the url of the menu item.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getUrl(): string
    {
        return $this->post->{'url'};
    }

    /**
     * Returns TRUE if the menu item has a parent.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function hasParent(): bool
    {
        return $this->parent !== null;
    }

    /**
     * Returns TRUE if the menu item is active.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function isActive(): bool
    {
        return Menus::isItemActive($this);
    }

    /**
     * Returns TRUE if the menu item has an active child.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function isChildActive(): bool
    {
        return Menus::isItemChildActive($this);
    }

    /**
     * Returns TRUE if the menu item is active or has an active child.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function isActiveOrChildActive(): bool
    {
        return Menus::isItemOrChildActive($this);
    }

}
