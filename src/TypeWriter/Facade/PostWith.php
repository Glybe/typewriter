<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\ViolationException;
use WP_Post;

/**
 * Class PostWith
 *
 * @method id(): ?int
 * @method content(array $filters = []): ?string
 * @method contentTruncated(int $wordCount = 20, string $ending = '...'): ?string
 * @method date(string $format): ?string
 * @method excerpt(array $filters = []): ?string
 * @method intro(): array
 * @method introHeading(): ?string
 * @method introLeading(): ?string
 * @method meta(string $metaKey, $defaultValue = null, bool $isSingle = true): mixed
 * @method metaText(string $metaKey, array $filters = []): ?string
 * @method permalink(): string
 * @method post(): WP_Post
 * @method thumbnail(string $thumbnailId): ?int
 * @method thumbnailData(string $thumbnailId, string $size = 'large'): ?array
 * @method thumbnailUrl(string $thumbnailId, string $size = 'large'): ?string
 * @method time(): ?int
 * @method timeAgo(string $format = '%s ago'): string
 * @method title(): ?string
 * @method type(): ?string
 * @method typeObject(): ?WP_Post_Type
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
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __call(string $name, array $arguments)
    {
        if (!method_exists(Post::class, $name))
            throw new ViolationException(sprintf('Method "%s" does not exist in "%s".', $name, Post::class), ViolationException::ERR_BAD_METHOD_CALL);

        return Post::useWith($this->post, fn() => Post::{$name}(...$arguments));
    }

}
