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
 * @method gallery(string $id): array
 * @method intro(): array
 * @method introHeading(): ?string
 * @method introLeading(): ?string
 * @method meta(string $metaKey, $defaultValue = null, bool $isSingle = true): mixed
 * @method metaText(string $metaKey, array $filters = []): ?string
 * @method parent(): ?PostWith
 * @method parentHas(): bool
 * @method permalink(): string
 * @method post(): WP_Post
 * @method relation(string $relationId, string $foreignType): array
 * @method relationIterator(string $relationId, string $foreignType): Generator
 * @method thumbnail(string $thumbnailId): ?int
 * @method thumbnailData(string $thumbnailId, string $size = 'large'): ?array
 * @method thumbnailUrl(string $thumbnailId, string $size = 'large'): ?string
 * @method time(): ?int
 * @method timeAgo(string $format = '%s ago'): string
 * @method title(): ?string
 * @method type(): ?string
 * @method typeObject(): ?WP_Post_Type
 *
 * @method translation(string $language): static
 * @method translations(): array
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
