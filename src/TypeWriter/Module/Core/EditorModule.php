<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function array_unshift;

/**
 * Class EditorModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class EditorModule extends Module
{

    /**
     * EditorModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adds features to the WordPress Editor.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('block_categories', [$this, 'onBlockCategories']);
    }

    /**
     * Invoked on block_categories filter hook.
     * Adds our custom gutenberg categories to the editor.
     *
     * @param array $categories
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onBlockCategories(array $categories): array
    {
        array_unshift($categories, [
            'slug' => 'tw-structure',
            'title' => __('TypeWriter Structure', 'tw')
        ]);

        array_unshift($categories, [
            'slug' => 'tw-seo',
            'title' => __('TypeWriter SEO', 'tw')
        ]);

        return Hooks::applyFilters('tw.editor.categories', $categories);
    }

}
