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
use WP_REST_Terms_Controller;
use WP_Term;
use function array_map;
use function array_merge;
use function get_taxonomies;
use function get_taxonomy;
use function get_terms;
use function register_taxonomy;
use function register_taxonomy_for_object_type;
use function taxonomy_exists;
use function unregister_taxonomy_for_object_type;

/**
 * Class Taxonomy
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class Taxonomy
{

    /** @var self[] */
    private static array $registered = [];

    protected string $id;
    protected array $labels = [];
    protected bool $builtIn = false;
    protected array $capabilities = [];
    protected string $description = '';
    protected bool $hierarchical = false;
    protected bool $public = true;
    protected bool $publiclyQueryable = true;
    protected string $queryVar;
    protected ?string $restBase;
    protected ?string $restController = WP_REST_Terms_Controller::class;
    protected ?array $rewrite = null;
    protected bool $showAdminColumn = false;
    protected bool $showInMenu = true;
    protected bool $showInMenus = true;
    protected bool $showInRest = true;
    protected bool $showTagCloud = true;
    protected bool $showUi = true;

    /** @var PostType[] */
    protected array $postTypes = [];

    /**
     * Taxonomy constructor.
     *
     * @param string $id
     * @param PostType[]|string[] $postTypes
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $id, array $postTypes = [])
    {
        $this->id = $id;
        $this->queryVar = $id;
        $this->restBase = $id;
        $this->postTypes = array_map(fn(PostType|string $postType) => $postType instanceof PostType ? $postType : PostType::get($postType), $postTypes);
    }

    /**
     * Registers the taxonomy.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function register(): void
    {
        if (isset(self::$registered[$this->id]))
            return;

        $postTypes = array_map(fn(PostType $postType) => $postType->getId(), $this->postTypes);
        $result = register_taxonomy($this->id, $postTypes, [
            'labels' => $this->labels,
            'capabilities' => $this->capabilities,
            'description' => $this->description,
            'hierarchical' => $this->hierarchical,
            'public' => $this->public,
            'publicly_queryable' => $this->publiclyQueryable,
            'query_var' => $this->queryVar,
            'rest_base' => $this->restBase,
            'rest_controller_class' => $this->restController,
            'rewrite' => $this->rewrite ?? false,
            'show_admin_column' => $this->showAdminColumn,
            'show_in_menu' => $this->showInMenu,
            'show_in_nav_menus' => $this->showInMenus,
            'show_in_rest' => $this->showInRest,
            'show_tagcloud' => $this->showTagCloud,
            'show_ui' => $this->showUi
        ]);

        if ($result instanceof WP_Error)
            throw new WordPressException($result->get_error_message(), WordPressException::ERR_REGISTER_FAILED);

        self::$registered[$this->id] = $this;
    }

    /**
     * Gets the id of the taxonomy.
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
     * Gets the labels of the taxonomy.
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
     * Gets if the taxonomy is built-in.
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
     * Gets the capabilities used by the taxonomy.
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * Gets the description of the taxonomy.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Gets if the taxonomy is hierarchical.
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
     * Gets the post types supported by the taxonomy.
     *
     * @return PostType[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getPostTypes(): array
    {
        return $this->postTypes;
    }

    /**
     * Gets if the taxonomy is public.
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
     * Gets if the taxonomy is publicly queryable.
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
     * Gets the query variable of the taxonomy.
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
     * Gets the rest base url of the taxonomy.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getRestBase(): ?string
    {
        return $this->restBase;
    }

    /**
     * Gets the rest controller of the taxonomy.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getRestController(): ?string
    {
        return $this->restController;
    }

    /**
     * Gets the rewrite rules of the taxonomy.
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
     * Gets if the taxonomy can show a column in the post list.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowAdminColumn(): bool
    {
        return $this->showAdminColumn;
    }

    /**
     * Gets if the taxonomy is visible in the side menu in wp-admin.
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
     * Gets the the taxonomy is available in the menu builder.
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
     * Gets if the taxonomy is available in the REST API.
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
     * Gets if the taxonomy can show a tag cloud.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getShowTagCloud(): bool
    {
        return $this->showTagCloud;
    }

    /**
     * Gets if the taxonomy can show ui in wp-admin.
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
     * Sets the labels of the taxonomy.
     *
     * name => Categories
     * singular_name => Category
     * search_items => Search Categories
     * popular_items => Popular Categories (Only for non-hierarchical taxonomies).
     * all_items => All Categories
     * parent_item => Parent Category (Only for hierarchical taxonomies).
     * parent_item_colon => Parent Category: (Only for hierarchical taxonomies).
     * edit_item => Edit Category
     * view_item => View Category
     * update_item => Update Category
     * add_new_item => Add New Category
     * new_item_name => New Category Name
     * separate_items_with_commas => Separate categories with commas (Only for non-hierarchical taxonomies).
     * add_or_remove_items => Add or remove categories (Only for non-hierarchical taxonomies).
     * choose_from_most_used => Choose from the most used categories (Only for non-hierarchical taxonomies).
     * not_found => No categories found
     * no_terms => No categories
     * must_used => Most Used
     *
     * @param string[] $labels
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
     * Sets the capabilities of the taxonomy.
     *
     * manage_terms => manage_categories
     * edit_terms => manage_categories
     * delete_terms => manage_categories
     * assign_terms => edit_posts
     *
     * @param string[] $capabilities
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setCapabilities(array $capabilities): static
    {
        $this->assertRegistered();
        $this->capabilities = $capabilities;

        return $this;
    }

    /**
     * Sets the description of the taxonomy.
     *
     * @param string $description
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setDescription(string $description): static
    {
        $this->assertRegistered();
        $this->description = $description;

        return $this;
    }

    /**
     * Sets if the taxonomy is hierarchical.
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
     * Sets the post types that can have the taxonomy assigned.
     *
     * @param array $postTypes
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setPostTypes(array $postTypes): static
    {
        if (isset(self::$registered[$this->id])) {
            foreach ($this->postTypes as $postType)
                unregister_taxonomy_for_object_type($this->id, $postType->getId());

            foreach ($postTypes as $postType)
                register_taxonomy_for_object_type($this->id, $postType->getId());
        }

        $this->postTypes = $postTypes;

        return $this;
    }

    /**
     * Sets if the taxonomy is public.
     *
     * @param bool $public
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setPublic(bool $public): static
    {
        $this->assertRegistered();
        $this->public = $public;

        return $this;
    }

    /**
     * Sets if the taxonomy is publicly queryable.
     *
     * @param bool $publiclyQueryable
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setPubliclyQueryable(bool $publiclyQueryable): static
    {
        $this->assertRegistered();
        $this->publiclyQueryable = $publiclyQueryable;

        return $this;
    }

    /**
     * Sets the query variable of the taxonomy.
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
     * Sets the rest base url of the taxonomy.
     *
     * @param string|null $restBase
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setRestBase(?string $restBase = null): static
    {
        $this->assertRegistered();
        $this->restBase = $restBase ?? $this->id;

        return $this;
    }

    /**
     * Sets the rest controller of the taxonomy.
     *
     * @param string $restController
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setRestController(string $restController): static
    {
        $this->assertRegistered();
        $this->restController = $restController;

        return $this;
    }

    /**
     * Sets the rewrite rules of the taxonomy.
     *
     * @param array $rewrite
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setRewrite(array $rewrite): static
    {
        $this->assertRegistered();
        $this->rewrite = $rewrite;

        return $this;
    }

    /**
     * Sets if the taxonomy should add a column in the post table in wp-admin.
     *
     * @param bool $showAdminColumn
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowAdminColumn(bool $showAdminColumn = true): static
    {
        $this->assertRegistered();
        $this->showAdminColumn = $showAdminColumn;

        return $this;
    }

    /**
     * Sets if the taxonomy is visible in the side menu in wp-admin.
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
     * Sets if the taxonomy is available in the menu builder.
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
     * Sets if the taxonomy is available in the REST API.
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
     * Sets if the taxonomy should show a tag cloud.
     *
     * @param bool $showTagCloud
     *
     * @return $this
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function setShowTagCloud(bool $showTagCloud = true): static
    {
        $this->assertRegistered();
        $this->showTagCloud = $showTagCloud;

        return $this;
    }

    /**
     * Sets if the taxonomy can show ui in wp-admin.
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
     * Gets terms for the taxonomy.
     *
     * @param array $options
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function getTerms(array $options = []): array
    {
        $terms = get_terms(array_merge($options, [
            'taxonomy' => $this->id
        ]));

        return array_map(fn(WP_Term $term) => new Term($term), $terms);
    }

    /**
     * Checks if the taxonomy is already registered. When it is, this method throws a
     * {@see WordPressException::ERR_DOING_IT_WRONG} exception.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function assertRegistered(): void
    {
        if (isset(self::$registered[$this->id])) {
            throw new WordPressException(sprintf('Taxonomy "%s" is already registered, therefore you cannot alter its settings.', $this->id), WordPressException::ERR_DOING_IT_WRONG);
        }
    }

    /**
     * Gets a taxonomy instance. This method converts non-typewriter taxonomies
     * to typewriter taxonomies.
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

        $registered = get_taxonomies();

        if (!isset($registered[$id])) {
            return null;
        }

        $wp = get_taxonomy($id);
        self::$registered[$id] = $tax = new static($id);

        $tax->labels = (array)$wp->labels;
        $tax->builtIn = $wp->_builtin;
        $tax->capabilities = (array)$wp->cap;
        $tax->description = $wp->description;
        $tax->hierarchical = $wp->hierarchical;
        $tax->postTypes = array_map(fn(string $postType) => PostType::get($postType), $wp->object_type ?? []);
        $tax->public = $wp->public;
        $tax->publiclyQueryable = $wp->publicly_queryable;
        $tax->queryVar = $wp->query_var;
        $tax->restBase = $wp->rest_base ?: null;
        $tax->restController = $wp->rest_controller_class ?: null;
        $tax->rewrite = $wp->rewrite ?: null;
        $tax->showAdminColumn = $wp->show_admin_column;
        $tax->showInMenu = $wp->show_in_menu;
        $tax->showInMenus = $wp->show_in_nav_menus;
        $tax->showInRest = $wp->show_in_rest;
        $tax->showTagCloud = $wp->show_tagcloud;
        $tax->showUi = $wp->show_ui;

        return $tax;
    }

    /**
     * Creates a new taxonomy instance.
     *
     * @param string $id
     * @param PostType[]|string[] $postTypes
     *
     * @return static
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function new(string $id, array $postTypes = []): static
    {
        return new static($id, $postTypes);
    }

    /**
     * Returns TRUE if the given taxonomy id is registered.
     *
     * @param string $id
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function registered(string $id): bool
    {
        return isset(self::$registered[$id]) || taxonomy_exists($id);
    }

}
