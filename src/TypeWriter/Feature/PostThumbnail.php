<?php
declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\Hooks;
use TypeWriter\Util\AdminUtil;
use function Columba\Util\dump;
use function get_post_meta;
use function get_post_thumbnail_id;
use function register_meta;
use function TypeWriter\tw;
use function wp_get_attachment_image_src;
use function wp_get_attachment_image_url;

/**
 * Class PostThumbnail
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class PostThumbnail extends Feature
{

	protected string $id;
	protected string $label;
	protected string $metaId;
	protected string $metaKey;
	protected string $postType;

	/**
	 * PostThumbnail constructor.
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
		$this->metaKey = "tw_{$this->metaId}_thumbnail_id";
		$this->postType = $postType;

		register_meta('post', $this->metaKey, [
			'object_subtype' => $this->postType,
			'single' => true,
			'show_in_rest' => true,
			'description' => 'An additional post thumbnail.',
			'type' => 'integer'
		]);

		Hooks::action('tw.admin-scripts.body', [$this, 'onAdminScriptsBody']);
		Hooks::action('delete_attachment', [$this, 'onDeleteAttachment']);
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
		if (!AdminUtil::isGutenbergView())
			return $scripts;

		$scripts[] = <<<CODE
			new tw.feature.PostThumbnail("{$this->id}", "{$this->label}", "{$this->metaKey}"); 
		CODE;

		return $scripts;
	}

	/**
	 * Invoked on delete_attachment action hook.
	 * Deletes any post association with the current attachment.
	 *
	 * @param int $postId
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onDeleteAttachment(int $postId): void
	{
		global $wpdb;

		tw()->getDatabase()->query()
			->deleteFrom($wpdb->postmeta)
			->where('meta_key', $this->metaKey)
			->and('meta_value', $postId);
	}

	/**
	 * Adds a new custom post thumbnail to the given post type.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param string $label
	 *
	 * @return static
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function add(string $postType, string $id, string $label): self
	{
		return new self($postType, $id, $label);
	}

	/**
	 * Gets the id of the used post thumbnail or zero if there isn't one.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param int    $postId
	 *
	 * @return int|null
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public static function get(string $postType, string $id, int $postId): ?int
	{
		$thumbnailId = intval($id === 'featured-image' ? get_post_thumbnail_id($postId) : get_post_meta($postId, "tw_{$postType}_{$id}_thumbnail_id", true));

		if ($thumbnailId === 0)
			return null;

		return $thumbnailId;
	}

	/**
	 * Gets the data of the used post thumbnail or NULL of there isn't one.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param int    $postId
	 * @param string $size
	 *
	 * @return array|null
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public static function getData(string $postType, string $id, int $postId, string $size = 'large'): ?array
	{
		$thumbnailId = self::get($postType, $id, $postId);

		if ($thumbnailId > 0 && get_post($thumbnailId))
			return wp_get_attachment_image_src($thumbnailId, $size);

		return null;
	}

	/**
	 * Gets the URL of the used post thumbnail or NULL if there isn't one.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param int    $postId
	 * @param string $size
	 *
	 * @return string|null
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public static function getUrl(string $postType, string $id, int $postId, string $size = 'large'): ?string
	{
		$thumbnailId = self::get($postType, $id, $postId);

		if ($thumbnailId > 0 && get_post($thumbnailId))
			return wp_get_attachment_image_url($thumbnailId, $size);

		return null;
	}

}
