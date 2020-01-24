<?php
/**
 * Copyright (c) 2019 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Router;

use Columba\Router\Response\HtmlResponse;
use Columba\Router\Router as ColumbaRouter;
use function TypeWriter\tw;

/**
 * Class Router
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Router
 * @since 1.0.0
 */
class Router extends ColumbaRouter
{

	/**
	 * Router constructor.
	 *
	 * @author Bas Milius <bas@ideemedia.nl>
	 * @since 1.0.0
	 */
	public function __construct()
	{
		parent::__construct(new HtmlResponse(), tw()->getCappuccino());
	}

}
