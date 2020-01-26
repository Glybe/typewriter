<?php
declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\MetaBox;

/**
 * Class PostThumbnail
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class PostThumbnail extends MetaBox
{

	protected string $metaId;
	protected string $metaKey;

	/**
	 * PostThumbnail constructor.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param string $label
	 * @param string $context
	 * @param string $priority
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(string $postType, string $id, string $label, string $context = 'side', string $priority = 'low')
	{
		$this->metaId = "{$postType}_{$id}";
		$this->metaKey = "{$this->metaKey}_thumbnail_id";

		parent::__construct($this->metaId, $label);

		$this->setContext($context);
		$this->setPriority($priority);
	}

	protected function register(): void
	{
		parent::register();
	}

}
