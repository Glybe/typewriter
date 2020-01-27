<?php
declare(strict_types=1);

namespace TypeWriter\Structure\Menu;

use WP_Term;

/**
 * Class Menu
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Structure\Menu
 * @since 1.0.0
 */
class Menu extends MenuObject
{

	protected WP_Term $term;

	/**
	 * Menu constructor.
	 *
	 * @param WP_Term $term
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(WP_Term $term)
	{
		$this->term = $term;
	}

	/**
	 * Gets the menu name.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getName(): string
	{
		return $this->term->name;
	}

	/**
	 * Gets the menu slug.
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getSlug(): string
	{
		return $this->term->slug;
	}

	/**
	 * Gets the menu term.
	 *
	 * @return WP_Term
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function getTerm(): WP_Term
	{
		return $this->term;
	}

}
