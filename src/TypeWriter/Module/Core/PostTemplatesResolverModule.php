<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Post;
use function TypeWriter\tw;

/**
 * Class PostTemplatesResolverModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class PostTemplatesResolverModule extends Module
{

	/**
	 * PostTemplatesLoaderModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Resolves additional templates inside themes.');
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function onInitialize(): void
	{
		Hooks::filter('archive_template', [$this, 'onArchiveTemplate']);
		Hooks::filter('page_template', [$this, 'onPageTemplate']);
		Hooks::filter('single_template', [$this, 'onSingleTemplate']);
		Hooks::filter('template_include', [$this, 'onTemplateInclude']);
	}

	/**
	 * Invoked on archive_template hook.
	 * Returns a possible archive template when an empty one is provided.
	 *
	 * @param string $template
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onArchiveTemplate(string $template): string
	{
		if (!empty($template))
			return $template;

		$tryFiles = ['archive'];

		if (is_author())
		{
			array_unshift($tryFiles, 'archive-author');
			array_unshift($tryFiles, 'archive-author-' . get_the_author_meta('login'));
			array_unshift($tryFiles, 'archive-author-' . get_the_author_meta('ID'));
		}

		if (is_date())
		{
			array_unshift($tryFiles, 'archive-date');

			if (is_year())
				array_unshift($tryFiles, 'archive-year');

			if (is_month())
				array_unshift($tryFiles, 'archive-month');

			if (is_day())
				array_unshift($tryFiles, 'archive-day');
		}

		$tryDirs = array_unique([get_template_directory(), get_stylesheet_directory()]);

		foreach ($tryDirs as $dir)
			foreach ($tryFiles as $file)
				if (is_file(($foundTemplate = $dir . '/template/' . get_post_type() . '/' . $file . '.php')))
					return $foundTemplate;
				else if (is_file(($foundTemplate = $dir . '/template/' . get_post_type() . '/' . $file . '.cappy')))
					return $foundTemplate;

		return $template;
	}

	/**
	 * Invoked on page_template hook.
	 * Returns a possible page template when an empty one is provided.
	 *
	 * @param string $template
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onPageTemplate(string $template): string
	{
		global $post;

		if (!($post instanceof WP_Post))
			return $template;

		return $this->findPossibleTemplate($template, [
			'default',
			$post->ID,
			$post->post_name
		]);
	}

	/**
	 * Invoked on single_template hook.
	 * Returns a possible single template when an empty one is provided.
	 *
	 * @param string $template
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onSingleTemplate(string $template): string
	{
		global $post;

		if (!($post instanceof WP_Post))
			return $template;

		return $this->findPossibleTemplate($template, [
			'default',
			$post->ID,
			$post->post_name
		]);
	}

	/**
	 * Invoked on template_include.
	 * Checks if we need to enter Cappuccino mode and otherwise gives control back to WordPress.
	 *
	 * @param string $template
	 *
	 * @return string|null
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onTemplateInclude(string $template): ?string
	{
		if (substr($template, -6) !== '.cappy')
			return $template;

		tw()->getCappuccino()->addPath(dirname($template));
		echo tw()->getCappuccino()->render(basename($template), []);

		return null;
	}

	/**
	 * Finds a template within the theme directories based on {@see $tryFiles}.
	 *
	 * @param string $template
	 * @param array  $tryFiles
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	private function findPossibleTemplate(string $template, array $tryFiles): string
	{
		global $post;

		if (!empty($template))
			return $template;

		$tryDirs = array_unique([get_template_directory(), get_stylesheet_directory()]);

		foreach ($tryDirs as $dir)
			foreach ($tryFiles as $file)
				if (is_file(($foundTemplate = $dir . '/template/' . $post->post_type . '/' . $file . '.php')))
					return $foundTemplate;
				else if (is_file(($foundTemplate = $dir . '/template/' . $post->post_type . '/' . $file . '.cappy')))
					return $foundTemplate;

		return $template;
	}

}