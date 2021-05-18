<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\ViolationException;
use WP_Post;

/**
 * Class PostWith
 *
 * @method int|null id()
 * @method string|null content(array $filters = [])
 * @method string|null contentTruncated(int $wordCount = 20, string $ending = '...')
 * @method string|null date(string $format)
 * @method string|null excerpt(array $filters = [])
 * @method int[] gallery(string $id)
 * @method string[] intro()
 * @method string|null introHeading()
 * @method string|null introLeading()
 * @method mixed meta(string $metaKey, $defaultValue = null, bool $isSingle = true)
 * @method string|null metaText(string $metaKey, array $filters = [])
 * @method string name()
 * @method PostWith|null parent()
 * @method bool parentHas()
 * @method string permalink()
 * @method WP_Post post()
 * @method int[] relation(string $relationId, string $foreignType)
 * @method Generator<PostWith> relationIterator(string $relationId, string $foreignType)
 * @method Term[] terms(string $taxonomy)
 * @method bool termHas(string $taxonomy, string|int $slugOrId)
 * @method int[] termIds(string $taxonomy)
 * @method int|null thumbnail(string $thumbnailId)
 * @method array|null thumbnailData(string $thumbnailId, string $size = 'large')
 * @method string|null thumbnailUrl(string $thumbnailId, string $size = 'large')
 * @method int|null time()
 * @method string timeAgo(string $format = '%s ago')
 * @method string|null title()
 * @method string|null type()
 * @method PostType typeObject()
 *
 * @method PostWith translation(string $language)
 * @method int[] translations()
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class PostWith
{

    private WP_Post $post;

    /**
     * PostWith constructor.
     *
     * @param WP_Post $post
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(WP_Post $post)
    {
        $this->post = $post;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @since 1.0.0
     * @author Bas Milius <bas@mili.us>
     */
    public final function __call(string $name, array $arguments): mixed
    {
        if (!method_exists(Post::class, $name)) {
            throw new ViolationException(sprintf('Method "%s" does not exist in "%s".', $name, Post::class), ViolationException::ERR_BAD_METHOD_CALL);
        }

        return Post::useWith($this->post, fn() => Post::{$name}(...$arguments));
    }

    /**
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function __debugInfo(): array
    {
        return (array)$this->post;
    }

}
