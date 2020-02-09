<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

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
	 * Gets the url of the given attachment with the given size.
	 *
	 * @hook tw.attachment.image-url (string $imageUrl, int $attachmentId, string $size): ?string
	 *
	 * @param int    $attachmentId
	 * @param string $size
	 *
	 * @return string|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function getImage(int $attachmentId, string $size = 'large'): ?string
	{
		$imageUrl = wp_get_attachment_image_url($attachmentId, $size);

		if (!empty($imageUrl))
			return Hooks::applyFilters('tw.attachment.image-url', $imageUrl, $attachmentId, $size);

		return null;
	}

	/**
	 * Gets the data of the given attachment with the given size.
	 *
	 * @param int    $attachmentId
	 * @param string $size
	 *
	 * @return array|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function getImageData(int $attachmentId, string $size = 'large'): ?array
	{
		$imageData = wp_get_attachment_image_src($attachmentId, $size);

		if (!$imageData)
			return null;

		return [
			'src' => $imageData[0],
			'width' => $imageData[1],
			'height' => $imageData[2]
		];
	}

}
