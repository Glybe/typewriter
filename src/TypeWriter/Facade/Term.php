<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use WP_Term;
use function get_term;

/**
 * Class Term
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
class Term
{

    /**
     * Term constructor.
     *
     * @param WP_Term $term
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(private WP_Term $term)
    {
    }

    /**
     * Gets the term id.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function id(): int
    {
        return $this->term->term_id;
    }

    /**
     * Gets the object count that have this term.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function count(): int
    {
        return $this->term->count;
    }

    /**
     * Gets the term description.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function description(): string
    {
        return $this->term->description;
    }

    /**
     * Gets the term name.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function name(): string
    {
        return $this->term->name;
    }

    /**
     * Gets the term slug.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function slug(): string
    {
        return $this->term->slug;
    }

    /**
     * Gets the parent term instance.
     *
     * @return static|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function parent(): ?static
    {
        return static::get($this->term->parent);
    }

    /**
     * Gets the parent term id.
     *
     * @return int
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function parentId(): int
    {
        return $this->term->parent;
    }

    /**
     * Gets the backing taxonomy instance.
     *
     * @return Taxonomy|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function taxonomy(): ?Taxonomy
    {
        return Taxonomy::get($this->term->taxonomy);
    }

    /**
     * Gets the backing taxonomy id.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function taxonomyId(): string
    {
        return $this->term->taxonomy;
    }

    /**
     * Gets a term instance from the given id.
     *
     * @param int $termId
     *
     * @return static|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function get(int $termId): ?static
    {
        $term = get_term($termId);

        if ($term instanceof WP_Term) {
            return new static($term);
        }

        return null;
    }

}
