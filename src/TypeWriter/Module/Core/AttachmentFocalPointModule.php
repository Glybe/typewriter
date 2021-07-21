<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Attachment;
use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Post;
use TypeWriter\Facade\Site;
use TypeWriter\Module\Module;
use WP_Post;
use function array_key_exists;
use function htmlspecialchars;
use function is_numeric;
use function update_post_meta;
use function wp_attachment_is_image;

/**
 * Class AttachmentFocalPointModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class AttachmentFocalPointModule extends Module
{

    /**
     * AttachmentFocalPointModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adds the ability to choose a focal point on attachments.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('attachment_fields_to_edit', [$this, 'onAttachmentFieldsToEdit']);
        Hooks::filter('attachment_fields_to_save', [$this, 'onAttachmentFieldsToSave']);
    }

    /**
     * Invoked on attachment_fields_to_edit filter hook.
     * Adds our focal point editor to the attachment details overlay.
     *
     * @param array $fields
     * @param WP_Post $post
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onAttachmentFieldsToEdit(array $fields, WP_Post $post): array
    {
        if (!wp_attachment_is_image($post) || !Hooks::applyFilters('tw.focal-point.enabled', true)) {
            return $fields;
        }

        $post = Post::with($post);
        $postId = $post->id();
        $alt = htmlspecialchars($post->title());
        $src = Attachment::getImage($postId, 'medium');

        $focalPointMeta = $post->meta('tw_focal_point');

        if ($focalPointMeta === null || !array_key_exists('x', $focalPointMeta) || !array_key_exists('y', $focalPointMeta) || !is_numeric($focalPointMeta['x']) || !is_numeric($focalPointMeta['y'])) {
            $focalPointMeta = ['x' => 50, 'y' => 50];
        }

        $fields['focal_point'] = [
            'label' => Site::translate('Focal Point', domain: 'tw'),
            'input' => 'html',
            'html' => <<<HTML
                <div class="tw-focal-point">
                    <input type="hidden" name="attachments[{$postId}][focal_point][x]" id="attachments[{$postId}][focal_point][x]" value="{$focalPointMeta['x']}"/>
                    <input type="hidden" name="attachments[{$postId}][focal_point][y]" id="attachments[{$postId}][focal_point][y]" value="{$focalPointMeta['y']}"/>

                    <img src="{$src}" alt="{$alt}"/>
                    <button class="tw-focal-point-selector" style="top: {$focalPointMeta['y']}%; left: {$focalPointMeta['x']}%;"></button>
                </div>

                <script type="text/javascript">tw.focalPoint();</script>
                HTML,
            'helps' => Site::translate('By default the Focal Point is set to the center of the image. Move the circle to change it.', domain: 'tw')
        ];

        return $fields;
    }

    /**
     * Invoked on attachment_fields_to_save filter hook.
     * Saves our focal point data to the meta of the attachment.
     *
     * @param array $post
     * @param array $attachment
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onAttachmentFieldsToSave(array $post, array $attachment): array
    {
        if (!array_key_exists('focal_point', $attachment) || !array_key_exists('x', $attachment['focal_point']) || !array_key_exists('y', $attachment['focal_point'])) {
            return $post;
        }

        ['x' => $x, 'y' => $y] = $attachment['focal_point'];

        update_post_meta($post['ID'], 'tw_focal_point', [
            'x' => (int)$x,
            'y' => (int)$y
        ]);

        return $post;
    }

}
