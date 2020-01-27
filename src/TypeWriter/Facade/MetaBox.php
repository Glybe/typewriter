<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\ViolationException;
use WP_Post;
use function add_meta_box;
use function basename;
use function defined;
use function TypeWriter\tw;
use function wp_enqueue_media;
use function wp_nonce_field;

/**
 * Class MetaBox
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
abstract class MetaBox
{

	protected string $id;
	protected string $label;
	protected string $context = 'side';
	protected string $priority = 'low';

	private bool $allowsQuickEdit = false;
	private bool $created = false;

	/**
	 * MetaBox constructor.
	 *
	 * @param string $id
	 * @param string $label
	 * @param bool   $autoRegister
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(string $id, string $label, bool $autoRegister = true)
	{
		$this->id = $id;
		$this->label = $label;

		if (!tw()->isAdmin())
			return;

		if ($autoRegister)
			Hooks::action('add_meta_boxes', [$this, 'onAddMetaBoxes']);

		Hooks::action('save_post', [$this, 'onPostSaveRequested']);
	}

	/**
	 * Invoked on the add_meta_boxes action hook.
	 * Registers our meta box with WordPress.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function onAddMetaBoxes(): void
	{
		$this->register();
	}

	/**
	 * Invoked on the save_post action hook.
	 * Used to save metadata of our post.
	 *
	 * @param int $postId
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function onPostSave(int $postId): void
	{
	}

	/**
	 * Invoked on the save_post action hook.
	 *
	 * @param int $postId
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @see MetaBox::onPostSave()
	 * @internal
	 */
	public function onPostSaveRequested(int $postId): void
	{
		if (!$this->allowsQuickEdit && defined('DOING_AJAX') && DOING_AJAX)
			return;

		$this->onPostSave($postId);
	}

	/**
	 * Invoked when the meta box is printed to the interface.
	 *
	 * @param WP_Post $post
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function onPrintMetaBox(WP_Post $post): void
	{
	}

	/**
	 * Sets if the meta box can save metadata when edited with quick edit.
	 *
	 * @param bool $allowsQuickEdit
	 *
	 * @return $this
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function setAllowsQuickEdit(bool $allowsQuickEdit = true): self
	{
		$this->allowsQuickEdit = $allowsQuickEdit;

		return $this;
	}

	/**
	 * Sets the context of the meta box.
	 *
	 * @param string $context
	 *
	 * @return $this
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function setContext(string $context): self
	{
		if ($this->created)
			throw new ViolationException('It is too late to set the meta box context as it has already been created.', ViolationException::ERR_TOO_LATE);

		$this->context = $context;

		return $this;
	}

	/**
	 * Sets the priority of the meta box.
	 *
	 * @param string $priority
	 *
	 * @return $this
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function setPriority(string $priority): self
	{
		if ($this->created)
			throw new ViolationException('It is too late to set the meta box priority as it has already been created.', ViolationException::ERR_TOO_LATE);

		$this->priority = $priority;

		return $this;
	}

	/**
	 * Creates a nonce field.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function createNonce(): void
	{
		wp_nonce_field(basename(__FILE__), $this->id);
	}

	/**
	 * Invoked when arguments are being processed for meta box creation.
	 *
	 * @param array $arguments
	 *
	 * @return array
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function getCreationArguments(array $arguments): array
	{
		$arguments['__block_editor_compatible_meta_box'] = true;
		$arguments['__back_compat_meta_box'] = false;

		return $arguments;
	}

	/**
	 * Returns the supported post types for the meta box.
	 *
	 * @return string[]
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function getSupportedPostTypes(): array
	{
		return [];
	}

	/**
	 * Registers the meta box.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function register(): void
	{
		if ($this->created)
			return;

		wp_enqueue_media();
		add_meta_box(
			$this->id,
			$this->label,
			[$this, 'onPrintMetaBox'],
			$this->getSupportedPostTypes(),
			$this->context,
			$this->priority,
			$this->getCreationArguments([])
		);

		$this->created = true;
	}

}
