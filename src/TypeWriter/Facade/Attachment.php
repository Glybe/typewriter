<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use function get_post_meta;
use function wp_get_attachment_image_src;
use function wp_get_attachment_image_url;

/**
 * Class Attachment
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class Attachment
{

    /**
     * Gets the focal point of the given attachment id. This will default
     * to 50% x 50% when a content editor did not set a focal point.
     *
     * @param int $attachmentId
     *
     * @return int[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function getFocalPoint(int $attachmentId): array
    {
        $focalPointMeta = get_post_meta($attachmentId, 'tw_focal_point', true);

        if (empty($focalPointMeta) || !array_key_exists('x', $focalPointMeta) || !array_key_exists('y', $focalPointMeta) || !is_numeric($focalPointMeta['x']) || !is_numeric($focalPointMeta['y'])) {
            return [50, 50];
        }

        return [
            (int)$focalPointMeta['x'],
            (int)$focalPointMeta['y']
        ];
    }

    /**
     * Gets the url of the given attachment with the given size.
     *
     * @hook tw.attachment.image-url (string $imageUrl, int $attachmentId, string $size): ?string
     *
     * @param int $attachmentId
     * @param string $size
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function getImage(int $attachmentId, string $size = 'large'): ?string
    {
        $imageUrl = wp_get_attachment_image_url($attachmentId, $size);

        if (!empty($imageUrl)) {
            return Hooks::applyFilters('tw.attachment.image-url', $imageUrl, $attachmentId, $size);
        }

        return null;
    }

    /**
     * Gets the data of the given attachment with the given size.
     *
     * @param int $attachmentId
     * @param string $size
     *
     * @return array|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function getImageData(int $attachmentId, string $size = 'large'): ?array
    {
        $imageData = wp_get_attachment_image_src($attachmentId, $size);

        if (!$imageData) {
            return null;
        }

        return [
            'src' => $imageData[0],
            'width' => $imageData[1],
            'height' => $imageData[2]
        ];
    }

}
