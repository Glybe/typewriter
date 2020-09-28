<?php
declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\Hooks;
use TypeWriter\Util\AdminUtil;
use function get_current_screen;
use function get_post_meta;
use function get_post_type;
use function is_array;
use function register_meta;

/**
 * Class Gallery
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 *
 * @todo When an attachment is deleted, these galleries should also delete that attachment from meta value.
 * @todo Check if the selected template supports galleries.
 */
class Gallery extends Feature
{

    protected string $id;
    protected string $label;
    protected string $metaId;
    protected string $metaKey;
    protected string $postType;

    /**
     * Gallery constructor.
     *
     * @param string $postType
     * @param string $id
     * @param string $label
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $postType, string $id, string $label)
    {
        parent::__construct(static::class);

        $this->id = $id;
        $this->label = $label;
        $this->metaId = "{$postType}_{$id}";
        $this->metaKey = "tw_{$this->metaId}_gallery";
        $this->postType = $postType;

        register_meta('post', $this->metaKey, [
            'object_subtype' => $this->postType,
            'single' => true,
            'show_in_rest' => [
                'schema' => [
                    'type' => 'array',
                    'items' => ['type' => 'integer']
                ]
            ],
            'description' => 'A post gallery.',
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

        if (!AdminUtil::isGutenbergView() || !$this->isPostTypeSupported($screen->post_type))
            return $scripts;

        $scripts[] = <<<CODE
			new tw.feature.Gallery("{$this->id}", "{$this->label}", "{$this->metaKey}"); 
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
     * Gets the given gallery by the given post.
     *
     * @param int $postId
     * @param string $galleryId
     *
     * @return int[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(int $postId, string $galleryId): array
    {
        $postType = get_post_type($postId);
        $metaKey = "tw_{$postType}_{$galleryId}_gallery";
        $metaValue = get_post_meta($postId, $metaKey, true);

        if (!is_array($metaValue))
            return [];

        return $metaValue;
    }

}
