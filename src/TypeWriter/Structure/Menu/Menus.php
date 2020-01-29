<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Menu;

use TypeWriter\Error\ViolationException;
use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Post;
use WP_Term;
use function get_nav_menu_locations;
use function get_registered_nav_menus;
use function get_term;
use function intval;
use function register_nav_menu;
use function wp_get_nav_menu_items;

/**
 * Class Menus
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Menu
 * @since 1.0.0
 */
class Menus
{

	private static array $getNavMenuItemsCache = [];
	private static array $menuCache = [];

	/**
	 * Returns TRUE if the given location exists.
	 *
	 * @param string $locationKey
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function hasLocation(string $locationKey): bool
	{
		$locations = get_registered_nav_menus();

		return isset($locations[$locationKey]);
	}

	/**
	 * Returns TRUE if the given location has a menu associated.
	 *
	 * @param string $locationKey
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function hasMenuAtLocation(string $locationKey): bool
	{
		return self::getMenuTerm($locationKey) instanceof WP_Term;
	}

	/**
	 * Registers a new menu location.
	 *
	 * @param string $locationKey
	 * @param string $locationLabel
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function registerLocation(string $locationKey, string $locationLabel): void
	{
		if (self::hasLocation($locationKey))
			throw new ViolationException(sprintf('Cannot register the menu location "%s". Another location with key "%s" has already been registered.', $locationLabel, $locationKey), ViolationException::ERR_DUPLICATE);

		register_nav_menu($locationKey, $locationLabel);
	}

	/**
	 * Gets the menu for the given location.
	 *
	 * @param string $locationKey
	 * @param int    $parent
	 * @param int    $objectParentId
	 *
	 * @return Menu|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function getMenu(string $locationKey, int $parent = 0, int $objectParentId = -1): ?Menu
	{
		$menuKey = "{$locationKey}_{$parent}_{$objectParentId}";

		if (isset(self::$menuCache[$menuKey]))
			return self::$menuCache[$menuKey];

		$term = self::getMenuTerm($locationKey);

		if ($term === null)
			return null;

		$menu = new Menu($term);

		self::getMenuStructure($menu, $parent, $objectParentId);

		return self::$menuCache[$menuKey] = $menu;
	}

	/**
	 * Returns TRUE if the given {@see MenuItem} is active.
	 *
	 * @param MenuItem $item
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function isItemActive(MenuItem $item): bool
	{
		$currentPostId = Post::id();

		return Hooks::applyFilters('tw.menus.is-item-active', $item->getPostId() === $currentPostId, $item, $currentPostId);
	}

	/**
	 * Returns TRUE if a child of the given {@see MenuItem} is active.
	 *
	 * @param MenuItem $item
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function isItemChildActive(MenuItem $item): bool
	{
		$isActive = false;

		foreach ($item->getItems() as $sub)
		{
			if (!self::isItemOrChildActive($sub))
				continue;

			$isActive = true;
			break;
		}

		return Hooks::applyFilters('tw.menus.is-item-child-active', $isActive, $item, Post::id());
	}

	/**
	 * Returns TRUE if the given {@see MenuItem} or a child of the given {@see MenuItem} is active.
	 *
	 * @param MenuItem $item
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function isItemOrChildActive(MenuItem $item): bool
	{
		return Hooks::applyFilters('tw.menus.is-item-or-child-active', self::isItemActive($item) || self::isItemChildActive($item), $item, Post::id());
	}

	/**
	 * Generates the root menu structure.
	 *
	 * @param Menu $menu
	 * @param int  $parentId
	 * @param int  $objectParentId
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	private static function getMenuStructure(Menu $menu, int $parentId = 0, int $objectParentId = -1): void
	{
		$items = static::$getNavMenuItemsCache[$menu->getName()] ??= wp_get_nav_menu_items($menu->getName()) ?: [];

		foreach ($items as $item)
		{
			if ($objectParentId >= 0 && intval($item->{'object_id'}) !== $objectParentId)
				continue;

			if (intval($item->{'menu_item_parent'}) !== $parentId)
				continue;

			$menuItem = new MenuItem($menu, $item);
			$menu->addItem($menuItem);

			self::getMenuStructureSub($menu, $menuItem);
		}
	}

	/**
	 * Generates a sub menu structure.
	 *
	 * @param Menu     $menu
	 * @param MenuItem $parent
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	private static function getMenuStructureSub(Menu $menu, MenuItem $parent): void
	{
		$items = static::$getNavMenuItemsCache[$menu->getName()] ??= wp_get_nav_menu_items($menu->getName()) ?: [];

		foreach ($items as $item)
		{
			if (intval($item->{'menu_item_parent'}) !== $parent->getPost()->ID)
				continue;

			$menuItem = new MenuItem($menu, $item, $parent);
			$parent->addItem($menuItem);

			self::getMenuStructureSub($menu, $menuItem);
		}
	}

	/**
	 * Gets the menu term for the given location, or NULL of there is no menu associated with the given location.
	 *
	 * @param string $locationKey
	 *
	 * @return WP_Term|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	private static function getMenuTerm(string $locationKey): ?WP_Term
	{
		$locations = get_nav_menu_locations();

		if (!isset($locations[$locationKey]))
			return null;

		return get_term($locations[$locationKey], 'nav_menu') ?? null;
	}

}
