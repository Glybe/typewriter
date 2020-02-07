<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function add_theme_support;
use function array_unique;
use function get_bloginfo;
use function get_post_type_object;
use function get_queried_object;
use function get_query_var;
use function get_taxonomy;
use function intval;
use function is_404;
use function is_archive;
use function is_array;
use function is_author;
use function is_category;
use function is_front_page;
use function is_home;
use function is_page;
use function is_post_type_archive;
use function is_search;
use function is_single;
use function is_tag;
use function is_tax;
use function post_type_archive_title;
use function reset;
use function single_post_title;
use function single_term_title;
use function substr;
use function TypeWriter\autoloader;
use function TypeWriter\tw;
use function zeroise;

/**
 * Class ThemeBaseModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class ThemeBaseModule extends Module
{

	/**
	 * ThemeBaseModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Basic setup for themes.');
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function onInitialize(): void
	{
		$themeDirectories = array_unique([get_template_directory(), get_stylesheet_directory()]);

		foreach ($themeDirectories as $dir)
		{
			autoloader()->addDirectory($dir);
			tw()->getCappuccino()->addPath($dir . '/template', 'theme');
		}

		Hooks::action('after_setup_theme', [$this, 'onWordPressAfterSetupTheme']);
		Hooks::action('wp_head', [$this, 'onWordPressHead']);
	}

	/**
	 * Invoked on the after_setup_theme action hook.
	 * Adds various theme supports values.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressAfterSetupTheme(): void
	{
		add_theme_support('post-thumbnails');
	}

	/**
	 * Invoked on the wp_head action hook.
	 * Adds various theme stuff the the output html.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 * @internal
	 */
	public final function onWordPressHead(): void
	{
		$this->generateTitle();
	}

	/**
	 * Generates the title of all pages.
	 *
	 * @hook tw.theme.title.not-found (string $text): string
	 * @hook tw.theme.title.search-results (string $text): string
	 * @hook tw.theme.title.parts (string[] $parts): string[]
	 * @hook tw.theme.title.separator (string $separator): string
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	private function generateTitle(): void
	{
		global $wp_locale;

		$description = get_bloginfo('description');
		$name = get_bloginfo('name');
		$parts = [];
		$separator = Hooks::applyFilters('tw.theme.title.separator', ' &ndash; ');

		$m = get_query_var('m');
		$year = get_query_var('year');
		$monthnum = get_query_var('monthnum');
		$day = get_query_var('day');
		$postTypeObject = null;

		if (is_single() || (is_home() && !is_front_page()) || (is_page() && !is_front_page()))
			$parts[] = single_post_title('', false);

		if (is_post_type_archive())
		{
			$postType = get_query_var('post_type');

			if (is_array($postType))
				$postType = reset($postType);

			$postTypeObject = get_post_type_object($postType);

			if (!$postTypeObject->has_archive)
				$parts[] = post_type_archive_title('', false);
		}

		if (is_category() || is_tag())
			$parts[] = single_term_title('', false);

		if (is_tax())
		{
			$term = get_queried_object();

			if ($term)
			{
				$tax = get_taxonomy($term->taxonomy);
				$parts[] = single_term_title($tax->labels->name, false);
			}
		}

		if (is_author() && !is_post_type_archive())
		{
			$author = get_queried_object();

			if ($author)
				$parts[] = $author->display_name;
		}

		if ($postTypeObject !== null && is_post_type_archive() && $postTypeObject->has_archive)
			$parts[] = post_type_archive_title('', false);

		if (is_archive() && !empty($m))
		{
			$my_year = substr($m, 0, 4);
			$my_month = $wp_locale->get_month(substr($m, 4, 2));
			$my_day = intval(substr($m, 6, 2));
			$parts[] = $my_year . ($my_month ? $my_month : '') . ($my_day ? $my_day : '');
		}

		if (is_archive() && !empty($year))
		{
			$parts[] = $year;

			if (!empty($monthnum))
				$parts[] = $wp_locale->get_month($monthnum);

			if (!empty($day))
				$parts[] = zeroise($day, 2);
		}

		if (is_search())
			$parts[] = Hooks::applyFilters('tw.theme.title.search-results', 'Search results for "%s"');

		if (is_404())
			$parts[] = Hooks::applyFilters('tw.theme.title.not-found', 'Page not found');

		if (!empty($name))
			$parts[] = $name;

		if (!empty($description))
			$parts[] = $description;

		$parts = Hooks::applyFilters('tw.theme.title.parts', $parts);

		if (count($parts) === 0)
			return;

		if (is_search())
			$parts[0] = sprintf($parts[0], get_query_var('s'));

		echo sprintf('<title>%s</title>', implode($separator, $parts));
	}

}
