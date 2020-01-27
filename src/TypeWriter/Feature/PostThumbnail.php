<?php
declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\Hooks;
use function register_meta;
use function TypeWriter\tw;

/**
 * Class PostThumbnail
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class PostThumbnail
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
		$this->id = $id;
		$this->label = $label;
		$this->metaId = "{$postType}_{$id}";
		$this->metaKey = "{$this->metaId}_thumbnail_id";
		$this->postType = $postType;

		register_meta('post', $this->metaKey, [
			'object_subtype' => $this->postType,
			'single' => true,
			'show_in_rest' => true,
			'description' => 'An additional post thumbnail.',
			'type' => 'integer'
		]);

		Hooks::action('admin_enqueue_scripts', [$this, 'onAdminEnqueueScripts']);
		Hooks::action('delete_attachment', [$this, 'onDeleteAttachment']);
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function getSupportedPostTypes(): array
	{
		return [$this->postType];
	}

	/**
	 * Invoked on admin_enqueue_scripts action hook.
	 * Loads the JS part of our post thumbnail editor.
	 *
	 * @param string $view
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onAdminEnqueueScripts(string $view): void
	{
		if (!in_array($view, ['post-new.php', 'post.php', 'media-upload-popup']))
			return;

		echo <<<CODE
<script type="text/javascript">
	window.addEventListener("load", function ()
	{
		new tw.feature.PostThumbnail("{$this->id}", "{$this->label}", "{$this->metaKey}");
	});
</script>
CODE;

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

}
