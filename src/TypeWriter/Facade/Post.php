<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Feature\PostThumbnail;
use WP_Post;

/**
 * Class Post
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class Post
{

	private static ?WP_Post $post = null;

	public static function has(): bool
	{
		return self::post() !== null;
	}

	public static function id(): ?int
	{
		return self::post()->ID ?? null;
	}

	public static function meta(string $metaKey, $defaultValue = null, bool $isSingle = true)
	{
		$metaValue = \get_post_meta(self::id(), $metaKey, $isSingle);

		if (empty($metaValue))
			return $defaultValue;

		return $metaValue;
	}

	public static function metaText(string $metaKey, array $filters = []): ?string
	{
		$metaValue = self::meta($metaKey, self::id());

		if ($metaValue === null)
			return null;

		return self::applyMultipleFilters($metaValue, $filters);
	}

	public static function next(): bool
	{
		if (!\have_posts())
			return false;

		\the_post();

		return true;
	}

	public static function post(): \WP_Post
	{
		return self::$post ?? get_post();
	}

	public static function thumbnail(string $thumbnailId): ?int
	{
		return PostThumbnail::get(self::type(), $thumbnailId, self::id());
	}

	public static function thumbnailData(string $thumbnailId, string $size = 'large'): ?array
	{
		return PostThumbnail::getData(self::type(), $thumbnailId, self::id(), $size);
	}

	public static function thumbnailUrl(string $thumbnailId, string $size = 'large'): ?string
	{
		return PostThumbnail::getUrl(self::type(), $thumbnailId, self::id(), $size);
	}

	public static function time(): ?int
	{
		return (int)\get_post_time('U', false, self::id(), false) ?: null;
	}

	public static function timeAgo(string $format = '%s ago'): string
	{
		return \sprintf($format, \human_time_diff(self::time() ?? 0));
	}

	public static function title(): ?string
	{
		$title = \get_the_title(self::id());

		return !empty($title) ? $title : null;
	}

	public static function type(): ?string
	{
		return \get_post_type(self::id()) ?: null;
	}

	public static function typeObject(): ?\WP_Post_Type
	{
		return \get_post_type_object(self::type()) ?: null;
	}

	/**
	 * Applies the given filters to the given value.
	 *
	 * @param mixed $value
	 * @param array $filters
	 *
	 * @return mixed
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected static function applyMultipleFilters($value, array $filters)
	{
		foreach ($filters as $filter)
			$value = Hooks::applyFilters($filter, $value);

		return $value;
	}

}
