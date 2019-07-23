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

namespace TypeWriter\Module\WP;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Post;

/**
 * Class PostTemplatesResolverModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\WP
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

		$tryFiles = ['archive.php'];

		if (is_author())
		{
			array_unshift($tryFiles, 'archive-author.php');
			array_unshift($tryFiles, 'archive-author-' . get_the_author_meta('login') . '.php');
			array_unshift($tryFiles, 'archive-author-' . get_the_author_meta('ID') . '.php');
		}

		if (is_date())
		{
			array_unshift($tryFiles, 'archive-date.php');

			if (is_year())
				array_unshift($tryFiles, 'archive-year.php');

			if (is_month())
				array_unshift($tryFiles, 'archive-month.php');

			if (is_day())
				array_unshift($tryFiles, 'archive-day.php');
		}

		$tryDirs = array_unique([get_template_directory(), get_stylesheet_directory()]);

		foreach ($tryDirs as $dir)
			foreach ($tryFiles as $file)
				if (is_file(($foundTemplate = $dir . '/template/' . get_post_type() . '/' . $file)))
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
			'default.php',
			$post->ID . '.php',
			$post->post_name . '.php'
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
			'default.php',
			$post->ID . '.php',
			$post->post_name . '.php'
		]);
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
				if (is_file(($foundTemplate = $dir . '/template/' . $post->post_type . '/' . $file)))
					return $foundTemplate;

		return $template;
	}

}
