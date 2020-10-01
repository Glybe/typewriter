<?php
declare(strict_types=1);

namespace Tolk;

use TypeWriter\Facade\PostType;
use TypeWriter\Feature\Feature;

/**
 * Class SidebarFeature
 *
 * @author Bas Milius <bas@mili.us>
 * @package Tolk
 * @since 1.0.0
 */
final class SidebarFeature extends Feature
{

    private PostType $postType;

    /**
     * SidebarFeature constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct(static::class);

        $this->postType = PostType::new('tolk-sidebar')
            ->setLabels([
                'name' => 'Sidebar',
                'singular_name' => 'Item'
            ])
            ->setCapabilityType('page')
            ->setMapMetaCap()
            ->setMenuIcon('dashicons-editor-quote')
            ->register();
    }

}
