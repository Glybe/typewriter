<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\WordPressException;
use WP_Error;
use function array_map;
use function get_object_taxonomies;
use function get_post_type_object;
use function get_post_types;
use function post_type_exists;
use function register_post_type;
use function register_taxonomy_for_object_type;
use function unregister_taxonomy_for_object_type;

/**
 * Class PostType
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class PostType
{

    /** @var self[] */
    private static array $registered = [];

    protected string $id;
    protected array $labels = [];
    protected bool $builtIn = false;
    protected bool $canExport = true;
    protected string $capabilityType;
    protected bool $excludeFromSearch = false;
    protected bool $hasArchive = false;
    protected bool $hierarchical = false;
    protected bool $mapMetaCap = false;
    protected ?string $menuIcon = null;
    protected ?int $menuPosition = null;
    protected bool $public = true;
    protected bool $publiclyQueryable = true;
    protected string $queryVar;
    protected ?array $rewrite = null;
    protected bool $showInAdminBar = true;
    protected bool $showInMenu = true;
    protected bool $showInMenus = true;
    protected bool $showInRest = true;
    protected bool $showUi = true;

    /** @var string[] */
    protected array $supports = ['title', 'editor'];

    /** @var Taxonomy[] */
    protected array $taxonomies = [];

    /**
     * PostType constructor.
     *
     * @param string $id
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->capabilityType = $id;
        $this->queryVar = $id;
    }

    /**
     * Registers the post type.
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function register(): static
    {
        if (isset(self::$registered[$this->id]))
            return self::$registered[$this->id];

        $result = register_post_type($this->id, [
            'labels' => $this->labels,
            'can_export' => $this->canExport,
            'capability_type' => $this->capabilityType,
            'exclude_from_search' => $this->excludeFromSearch,
            'has_archive' => $this->hasArchive,
            'hierarchical' => $this->hierarchical,
            'map_meta_cap' => $this->mapMetaCap,
            'menu_icon' => $this->menuIcon,
            'menu_position' => $this->menuPosition,
            'public' => $this->public,
            'publicly_queryable' => $this->publiclyQueryable,
            'rewrite' => $this->rewrite ?? false,
            'show_in_admin_bar' => $this->showInAdminBar,
            'show_in_menu' => $this->showInMenu,
            'show_in_nav_menus' => $this->showInMenus,
            'show_in_rest' => $this->showInRest,
            'show_ui' => $this->showUi,
            'supports' => $this->supports,
            'taxonomies' => array_map(fn(Taxonomy $tax) => $tax->getId(), $this->taxonomies)
        ]);

        if ($result instanceof WP_Error)
            throw new WordPressException($result->get_error_message(), WordPressException::ERR_REGISTER_FAILED);

        return self::$registered[$this->id] = $this;
    }

    /**
     * Gets the id of the post type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Gets the labels of the post type.
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * Gets if the post type is a built-in post type.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getBuiltIn(): bool
    {
        return $this->builtIn;
    }

    /**
     * Gets if the post type can be exported.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getCanExport(): bool
    {
        return $this->canExport;
    }

    /**
     * Gets the base capability id of the post type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getCapabilityType(): string
    {
        return $this->capabilityType;
    }

    /**
     * Gets if the post type is available in the main search query.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getExcludeFromSearch(): bool
    {
        return $this->excludeFromSearch;
    }

    /**
     * Gets if the post type has an archive.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getHasArchive(): bool
    {
        return $this->hasArchive;
    }

    /**
     * Gets if te post type is hierarchical.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getHierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * Gets if the capabilities of the post type should map to the defaults.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getMapMetaCap(): bool
    {
        return $this->mapMetaCap;
    }

    /**
     * Gets the icon of the post type in the side menu in wp-admin.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getMenuIcon(): ?string
    {
        return $this->menuIcon;
    }

    /**
     * Gets the position of the post type in the side menu in wp-admin.
     *
     * @return int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getMenuPosition(): ?int
    {
        return $this->menuPosition;
    }

    /**
     * Gets if the post type is public.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getPublic(): bool
    {
        return $this->public;
    }

    /**
     * Gets if the post type can be publicly queried.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getPubliclyQueryable(): bool
    {
        return $this->publiclyQueryable;
    }

    /**
     * Gets the query variable of the post type.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getQueryVar(): string
    {
        return $this->queryVar;
    }

    /**
     * Gets the rewrite rules of the post type.
     *
     * @return array|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getRewrite(): ?array
    {
        return $this->rewrite;
    }

    /**
     * Gets if the post type is visible in the admin bar in wp-admin.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowInAdminBar(): bool
    {
        return $this->showInAdminBar;
    }

    /**
     * Gets if the post type is visible in the side menu in wp-admin.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowInMenu(): bool
    {
        return $this->showInMenu;
    }

    /**
     * Gets if the post type is available in the menu builder.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowInMenus(): bool
    {
        return $this->showInMenus;
    }

    /**
     * Gets if the post type is available in the REST API.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowInRest(): bool
    {
        return $this->showInRest;
    }

    /**
     * Gets if the post type can show any ui in wp-admin.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowUi(): bool
    {
        return $this->showUi;
    }

    /**
     * Gets the features the post type supports.
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * Gets the taxonomies registered with the post type.
     *
     * @return Taxonomy[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * Sets the labels of the post type.
     *
     * @param array $labels
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setLabels(array $labels): static
    {
        $this->assertRegistered();
        $this->labels = $labels;

        return $this;
    }

    /**
     * Sets if the post type can be exported.
     *
     * @param bool $canExport
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setCanExport(bool $canExport = true): static
    {
        $this->assertRegistered();
        $this->canExport = $canExport;

        return $this;
    }

    /**
     * Sets the base capability id of the post type.
     *
     * @param string|null $capabilityType
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setCapabilityType(?string $capabilityType = null): static
    {
        $this->assertRegistered();
        $this->capabilityType = $capabilityType ?? $this->id;

        return $this;
    }

    /**
     * Sets if the post type is available in the main search query.
     *
     * @param bool $excludeFromSearch
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setExcludeFromSearch(bool $excludeFromSearch = true): static
    {
        $this->assertRegistered();
        $this->excludeFromSearch = $excludeFromSearch;

        return $this;
    }

    /**
     * Sets if the post type has an archive.
     *
     * @param bool $hasArchive
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setHasArchive(bool $hasArchive = true): static
    {
        $this->assertRegistered();
        $this->hasArchive = $hasArchive;

        return $this;
    }

    /**
     * Sets if the post type is heirarchical.
     *
     * @param bool $hierarchical
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setHierarchical(bool $hierarchical = true): static
    {
        $this->assertRegistered();
        $this->hierarchical = $hierarchical;

        return $this;
    }

    /**
     * Sets if the capabilities of the post type should be mapped to the defaults.
     *
     * @param bool $mapMetaCap
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setMapMetaCap(bool $mapMetaCap = true): static
    {
        $this->assertRegistered();
        $this->mapMetaCap = $mapMetaCap;

        return $this;
    }

    /**
     * Sets the menu icon of the post type in wp-admin.
     *
     * @param string $menuIcon
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setMenuIcon(string $menuIcon): static
    {
        $this->assertRegistered();
        $this->menuIcon = $menuIcon;

        return $this;
    }

    /**
     * Sets the menu location of the post type in wp-admin.
     *
     * @param int $menuPosition
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setMenuLocation(int $menuPosition): static
    {
        $this->assertRegistered();
        $this->menuPosition = $menuPosition;

        return $this;
    }

    /**
     * Sets if the post type is public.
     *
     * @param bool $public
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setPublic(bool $public = true): static
    {
        $this->assertRegistered();
        $this->public = $public;

        return $this;
    }

    /**
     * Sets if the post type can be queried publicly on front-end.
     *
     * @param bool $publiclyQueryable
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setPubliclyQueryable(bool $publiclyQueryable = true): static
    {
        $this->assertRegistered();
        $this->publiclyQueryable = $publiclyQueryable;

        return $this;
    }

    /**
     * Sets the query variable of the post type.
     *
     * @param string|null $queryVar
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setQueryVar(?string $queryVar = null): static
    {
        $this->assertRegistered();
        $this->queryVar = $queryVar ?? $this->id;

        return $this;
    }

    /**
     * Sets the rewrite rules of the post type.
     *
     * @param array|null $rewrite
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setRewrite(?array $rewrite = null): static
    {
        $this->assertRegistered();
        $this->rewrite = $rewrite;

        return $this;
    }

    /**
     * Sets if the post type is visible in the admin bar in wp-admin.
     *
     * @param bool $showInAdminBar
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowInAdminBar(bool $showInAdminBar = true): static
    {
        $this->assertRegistered();
        $this->showInAdminBar = $showInAdminBar;

        return $this;
    }

    /**
     * Sets if the post type is visible in the side menu in wp-admin.
     *
     * @param bool $showInMenu
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowInMenu(bool $showInMenu = true): static
    {
        $this->assertRegistered();
        $this->showInMenu = $showInMenu;

        return $this;
    }

    /**
     * Sets if the post type is available in the menu builder.
     *
     * @param bool $showInMenus
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowInMenus(bool $showInMenus = true): static
    {
        $this->assertRegistered();
        $this->showInMenus = $showInMenus;

        return $this;
    }

    /**
     * Sets if the post type is available in the REST API.
     *
     * @param bool $showInRest
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowInRest(bool $showInRest = true): static
    {
        $this->assertRegistered();
        $this->showInRest = $showInRest;

        return $this;
    }

    /**
     * Sets if any UI of the post type should show up in wp-admin.
     *
     * @param bool $showUi
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowUi(bool $showUi = true): static
    {
        $this->assertRegistered();
        $this->showUi = $showUi;

        return $this;
    }

    /**
     * Sets the features supported by the post type.
     *
     * @param string[] $supports
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setSupports(array $supports): static
    {
        $this->assertRegistered();
        $this->supports = $supports;

        return $this;
    }

    /**
     * Sets the taxonomies supported by the post type.
     *
     * @param Taxonomy[] $taxonomies
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setTaxonomies(array $taxonomies): static
    {
        if (isset(self::$registered[$this->id])) {
            foreach ($this->taxonomies as $tax) {
                unregister_taxonomy_for_object_type($tax->getId(), $this->id);
            }

            foreach ($taxonomies as $tax) {
                register_taxonomy_for_object_type($tax->getId(), $this->id);
            }
        }

        $this->taxonomies = $taxonomies;

        return $this;
    }

    /**
     * Checks if the post type is already registered. When it is, this method throws a
     * {@see WordPressException::ERR_DOING_IT_WRONG} exception.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function assertRegistered(): void
    {
        if (isset(self::$registered[$this->id])) {
            throw new WordPressException(sprintf('Post type "%s" is already registered, therefore you cannot alter its settings.', $this->id), WordPressException::ERR_DOING_IT_WRONG);
        }
    }

    /**
     * Gets a post type instance. This method converts non-typewriter post types
     * to typewriter post types.
     *
     * @param string $id
     *
     * @return static|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(string $id): ?static
    {
        if (isset(self::$registered[$id])) {
            return self::$registered[$id];
        }

        $registered = get_post_types();

        if (!isset($registered[$id])) {
            return null;
        }

        $wp = get_post_type_object($id);
        self::$registered[$id] = $pt = new self($id);

        $pt->labels = (array)$wp->labels;
        $pt->builtIn = $wp->_builtin;
        $pt->canExport = $wp->can_export;
        $pt->capabilityType = $wp->capability_type;
        $pt->excludeFromSearch = $wp->exclude_from_search ?? false;
        $pt->hasArchive = $wp->has_archive;
        $pt->hierarchical = $wp->hierarchical;
        $pt->mapMetaCap = $wp->map_meta_cap;
        $pt->menuIcon = $wp->menu_icon;
        $pt->menuPosition = $wp->menu_position;
        $pt->public = $wp->public;
        $pt->publiclyQueryable = $wp->publicly_queryable ?? true;
        $pt->queryVar = $wp->query_var ?: $pt->id;
        $pt->rewrite = $wp->rewrite ?: null;
        $pt->showInAdminBar = $wp->show_in_admin_bar ?? true;
        $pt->showInMenu = $wp->show_in_menu ?? true;
        $pt->showInMenus = $wp->show_in_nav_menus ?? true;
        $pt->showInRest = $wp->show_in_rest;
        $pt->showUi = $wp->show_ui ?? true;
        $pt->supports = $wp->supports ?? [];
        $pt->taxonomies = array_map(fn(string $tax) => Taxonomy::get($tax), get_object_taxonomies($id));

        return $pt;
    }

    /**
     * Creates a new post type instance.
     *
     * @param string $id
     *
     * @return static
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function new(string $id): static
    {
        return new static($id);
    }

    /**
     * Returns TRUE if the given post type id is registered.
     *
     * @param string $id
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function registered(string $id): bool
    {
        return isset(self::$registered[$id]) || post_type_exists($id);
    }

}
