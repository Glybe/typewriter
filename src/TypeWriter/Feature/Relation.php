<?php
/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\Hooks;
use TypeWriter\Util\AdminUtil;
use WP_Post;
use function array_filter;
use function array_map;
use function array_values;
use function get_current_screen;
use function get_post_meta;
use function intval;
use function register_meta;

/**
 * Class Relation
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class Relation extends Feature
{

    protected string $id;
    protected string $label;
    protected string $foreignType;
    protected string $metaId;
    protected string $metaKey;
    protected string $postType;
    protected bool $isMultiple;

    /**
     * Relation constructor.
     *
     * @param string $postType
     * @param string $id
     * @param string $label
     * @param string $foreignType
     * @param bool $isMultiple
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $postType, string $id, string $label, string $foreignType, bool $isMultiple = true)
    {
        parent::__construct(static::class);

        $this->id = $id;
        $this->label = $label;
        $this->foreignType = $foreignType;
        $this->metaId = "{$postType}_{$id}_{$foreignType}";
        $this->metaKey = "tw_{$this->metaId}_relation";
        $this->postType = $postType;
        $this->isMultiple = $isMultiple;

        register_meta('post', $this->metaKey, [
            'object_subtype' => $this->postType,
            'single' => true,
            'show_in_rest' => [
                'schema' => [
                    'type' => 'array',
                    'items' => ['type' => 'integer']
                ]
            ],
            'description' => 'Defines a relationship between objects.',
            'type' => 'array'
        ]);

        Hooks::action('tw.admin-scripts.body', [$this, 'onAdminScriptsBody']);
    }

    /**
     * Invoked on tw.admin-scripts.body filter hook.
     * Loads the JS part of our post thumbnail editor.
     *
     * @param string[] $scripts
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminScriptsBody(array $scripts): array
    {
        $screen = get_current_screen();

        if (!AdminUtil::isGutenbergView() || !$this->isPostTypeSupported($screen->post_type)) {
            return $scripts;
        }

        $multiple = $this->isMultiple ? 'true' : 'false';

        $scripts[] = <<<CODE
			new tw.feature.Relation("{$this->id}", "{$this->label}", "{$this->metaKey}", "{$this->foreignType}", {$multiple}); 
		CODE;

        return $scripts;
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function getSupportedPostTypes(): ?array
    {
        return [$this->postType];
    }

    /**
     * Gets the object id's in the given relation.
     *
     * @param WP_Post $post
     * @param string $id
     * @param string $foreignType
     *
     * @return int[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(WP_Post $post, string $id, string $foreignType): array
    {
        $metaId = "{$post->post_type}_{$id}_{$foreignType}";
        $metaKey = "tw_{$metaId}_relation";

        $objectIds = get_post_meta($post->ID, $metaKey, true) ?: [];
        $objectIds = array_map(fn($val): int => intval($val), $objectIds);
        $objectIds = array_filter($objectIds, fn(int $val): bool => $val > 0);

        return array_values($objectIds);
    }

}
