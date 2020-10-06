<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use Columba\Util\StringUtil;
use Generator;
use TypeWriter\Error\ViolationException;
use TypeWriter\Feature\Gallery;
use TypeWriter\Feature\IntroTextMetaFields;
use TypeWriter\Feature\PostThumbnail;
use TypeWriter\Feature\Relation;
use WP_Post;
use WP_Post_Type;
use function get_permalink;
use function get_post;
use function get_post_meta;
use function get_post_time;
use function get_post_type;
use function get_post_type_object;
use function get_the_content;
use function get_the_date;
use function get_the_excerpt;
use function get_the_title;
use function have_posts;
use function human_time_diff;
use function sprintf;
use function the_post;

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

    /**
     * Returns TRUE if there is a current post.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function has(): bool
    {
        return self::post() !== null;
    }

    /**
     * Resets to the main post.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function reset(): void
    {
        static::$post = null;
    }

    /**
     * Uses the given post for further calls.
     *
     * @param WP_Post $post
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function use(WP_Post $post): void
    {
        static::$post = $post;
    }

    /**
     * Uses the given post for the given call.
     *
     * @param WP_Post $post
     * @param callable $fn
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function useWith(WP_Post $post, callable $fn)
    {
        self::use($post);

        $result = $fn();

        self::reset();

        return $result;
    }

    /**
     * Returns a new {@see PostWith} instance.
     *
     * @param WP_Post $post
     *
     * @return PostWith
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function with(WP_Post $post): PostWith
    {
        return new PostWith($post);
    }

    /**
     * Gets the ID of the current post.
     *
     * @return int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function id(): ?int
    {
        return self::post()->ID ?? null;
    }

    /**
     * Gets the content of the current post.
     *
     * @param array $filters
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function content(array $filters = []): ?string
    {
        $content = self::post()->post_content;

        if (empty($content)) {
            return null;
        }

        return self::applyMultipleFilters($content, $filters);
    }

    /**
     * Gets the content, but truncated, of the current post.
     *
     * @param int $wordCount
     * @param string $ending
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function contentTruncated(int $wordCount = 20, string $ending = '...'): ?string
    {
        $content = self::content();

        if ($content === null) {
            return null;
        }

        return StringUtil::truncateText($content, $wordCount, $ending);
    }

    /**
     * Gets the date in the given format of the current post.
     *
     * @param string $format
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function date(string $format): ?string
    {
        return get_the_date($format, self::id()) ?: null;
    }

    /**
     * Gets the excerpt of the current post.
     *
     * @param array $filters
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function excerpt(array $filters = []): ?string
    {
        $excerpt = get_the_excerpt(self::id());
        $excerpt = !empty($excerpt) ? $excerpt : get_the_content(self::id());

        if (empty($excerpt)) {
            return null;
        }

        return self::applyMultipleFilters($excerpt, $filters);
    }

    /**
     * Gets the gallery with the given id of the current post.
     *
     * @param string $id
     *
     * @return int[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function gallery(string $id): array
    {
        return Gallery::get(self::id(), $id);
    }

    /**
     * Gets the intro text of the current post.
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see IntroTextMetaFields
     */
    public static function intro(): array
    {
        return IntroTextMetaFields::get(self::id());
    }

    /**
     * Gets the intro text heading of the current post.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see IntroTextMetaFields
     */
    public static function introHeading(): ?string
    {
        $text = self::intro()['heading'] ?? '';

        if (empty($text)) {
            return null;
        }

        return $text;
    }

    /**
     * Gets the intro text leading of the current post.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see IntroTextMetaFields
     */
    public static function introLeading(): ?string
    {
        $text = self::intro()['leading'] ?? '';

        if (empty($text)) {
            return null;
        }

        return $text;
    }

    /**
     * Gets a meta value of the current post.
     *
     * @param string $metaKey
     * @param null $defaultValue
     * @param bool $isSingle
     *
     * @return mixed|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function meta(string $metaKey, $defaultValue = null, bool $isSingle = true)
    {
        $metaValue = get_post_meta(self::id(), $metaKey, $isSingle);

        if (empty($metaValue)) {
            return $defaultValue;
        }

        return $metaValue;
    }

    /**
     * Gets a meta value of the current post as text.
     *
     * @param string $metaKey
     * @param array $filters
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function metaText(string $metaKey, array $filters = []): ?string
    {
        $metaValue = self::meta($metaKey, self::id());

        if ($metaValue === null) {
            return null;
        }

        return self::applyMultipleFilters($metaValue, $filters);
    }

    /**
     * Iterates to the next post.
     *
     * @return bool
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function next(): bool
    {
        if (!have_posts()) {
            return false;
        }

        the_post();

        return true;
    }

    /**
     * Gets the permalink of the current post.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function permalink(): string
    {
        return get_permalink(self::id());
    }

    /**
     * Gets the current post.
     *
     * @return WP_Post
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function post(): WP_Post
    {
        return self::$post ?? get_post();
    }

    /**
     * Gets objects that are linked to the current post.
     *
     * @param string $relationId
     * @param string $foreignType
     *
     * @return int[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function relation(string $relationId, string $foreignType): array
    {
        return Relation::get(self::post(), $relationId, $foreignType);
    }

    /**
     * Gets objects that are linked to the current post as Post instances.
     *
     * @param string $relationId
     * @param string $foreignType
     *
     * @return Generator
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function relationIterator(string $relationId, string $foreignType): Generator
    {
        $postIds = static::relation($relationId, $foreignType);

        foreach ($postIds as $postId) {
            yield Post::with(get_post($postId));
        }
    }

    /**
     * Gets the given id of the thumbnail of the current post.
     *
     * @param string $thumbnailId
     *
     * @return int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see PostThumbnail
     */
    public static function thumbnail(string $thumbnailId): ?int
    {
        return PostThumbnail::get(self::type(), $thumbnailId, self::id());
    }

    /**
     * Gets the data of the given thumbnail of the current post.
     *
     * @param string $thumbnailId
     * @param string $size
     *
     * @return array|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see PostThumbnail
     */
    public static function thumbnailData(string $thumbnailId, string $size = 'large'): ?array
    {
        return PostThumbnail::getData(self::type(), $thumbnailId, self::id(), $size);
    }

    /**
     * Gets the url of the given thumbnail of the current post.
     *
     * @param string $thumbnailId
     * @param string $size
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see PostThumbnail
     */
    public static function thumbnailUrl(string $thumbnailId, string $size = 'large'): ?string
    {
        return PostThumbnail::getUrl(self::type(), $thumbnailId, self::id(), $size);
    }

    /**
     * Gets the date of the current post.
     *
     * @return int|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function time(): ?int
    {
        return (int)get_post_time('U', false, self::id(), false) ?: null;
    }

    /**
     * Returns the date ago of the current post.
     *
     * @param string $format
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function timeAgo(string $format = '%s ago'): string
    {
        return sprintf($format, human_time_diff(self::time() ?? 0));
    }

    /**
     * Gets the title of the current post.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function title(): ?string
    {
        $title = get_the_title(self::id());

        return !empty($title) ? $title : null;
    }

    /**
     * Gets the post type id.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function type(): ?string
    {
        return get_post_type(self::id()) ?: null;
    }

    /**
     * Gets the post type object instance.
     *
     * @return WP_Post_Type|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function typeObject(): ?WP_Post_Type
    {
        return get_post_type_object(self::type()) ?: null;
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
        foreach ($filters as $filter) {
            $value = Hooks::applyFilters($filter, $value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function __call($name, $arguments)
    {
        throw new ViolationException(sprintf('The method "%s" does not exist in %s.', $name, static::class), ViolationException::ERR_BAD_METHOD_CALL);
    }

}
