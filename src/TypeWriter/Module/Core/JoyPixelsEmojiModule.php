<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Module\Module;

/**
 * Class JoyPixelsEmojiModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class JoyPixelsEmojiModule extends Module
{

	/**
	 * JoyPixelsEmojiModule constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('Let WordPress use the emoji\'s of JoyPixels.');
	}

}
