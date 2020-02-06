<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Cappuccino\TokenParser;

use TypeWriter\Facade\Template;

/**
 * Class HeaderTokenParser
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino\TokenParser
 * @since 1.0.0
 */
final class FooterTokenParser extends AbstractRenderTokenParser
{

	/**
	 * FooterTokenParser constructor.
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct('footer');
	}

	/**
	 * {@inheritDoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function generateCall(): string
	{
		return sprintf('echo %s::renderFooter();', Template::class);
	}

}
