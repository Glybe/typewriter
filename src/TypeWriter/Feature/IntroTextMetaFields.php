<?php
/**
 * Copyright (c) 2020 - IdeeMedia <info@ideemedia.nl>
 * Copyright (c) 2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Feature;

use TypeWriter\Feature\MetaFields\MetaFields;
use TypeWriter\Feature\MetaFields\TextareaControlMetaField;
use TypeWriter\Feature\MetaFields\TextControlMetaField;
use function get_post_meta;

/**
 * Class IntroTextMetaFields
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Feature
 * @since 1.0.0
 */
class IntroTextMetaFields extends MetaFields
{

	/**
	 * IntroTextMetaFields constructor.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('tw-intro-text', 'tw_intro_text', 'Intro Text', '<strong>This is usually an inviting text.</strong>');
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function registerFields(): void
	{
		$this->register(new TextControlMetaField('heading', 'Heading', 'Welcome!'));
		$this->register(new TextareaControlMetaField('leading', 'Leading', 'We create websites, apps and more!'));
	}

	/**
	 * Gets the intro text for the given post.
	 *
	 * @param int $postId
	 *
	 * @return string[]
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function get(int $postId): array
	{
		$metaValue = get_post_meta($postId, 'tw_intro_text', true);

		return [
			'heading' => $metaValue['heading'] ?? '',
			'leading' => $metaValue['leading'] ?? ''
		];
	}

}
