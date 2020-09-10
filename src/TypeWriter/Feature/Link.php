<?php
/*
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Facade\Hooks;
use TypeWriter\Util\AdminUtil;

/**
 * Class Link
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class Link extends Feature
{

	protected string $id;
	protected string $label;
	protected string $foreignType;
	protected string $metaId;
	protected string $metaKey;
	protected string $postType;

	/**
	 * Link constructor.
	 *
	 * @param string $postType
	 * @param string $id
	 * @param string $label
	 * @param string $foreignType
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(string $postType, string $id, string $label, string $foreignType)
	{
		parent::__construct(static::class);

		$this->id = $id;
		$this->label = $label;
		$this->foreignType = $foreignType;
		$this->metaId = "{$postType}_{$id}_{$foreignType}";
		$this->metaKey = "tw_{$this->metaId}_link";
		$this->postType = $postType;

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
			new tw.feature.Link("{$this->id}", "{$this->label}", "{$this->metaKey}", "{$this->foreignType}"); 
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

}
