<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino\Controller;

/**
 * Class Controller
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino\Controller
 * @since 1.0.0
 */
abstract class Controller
{

	/**
	 * Returns the context that Cappuccino will receive.
	 *
	 * @return array
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public abstract function getContext(): array;

}
