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

namespace TypeWriter\Cappuccino;

use Columba\Router\Renderer\CappuccinoRenderer as ColumbaCappuccinoRenderer;
use TypeWriter\Facade\Site;
use TypeWriter\Facade\Template;
use function array_merge;
use function TypeWriter\tw;
use const TypeWriter\PUBLIC_DIR;

/**
 * Class CappuccinoRenderer
 *
 * @author Bas Milius <bas@ideemedia.nl>
 * @package TypeWriter\Cappuccino
 * @since 1.0.0
 */
final class CappuccinoRenderer extends ColumbaCappuccinoRenderer
{

	/**
	 * CappuccinoRenderer constructor.
	 *
	 * @param array $options
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function __construct(array $options = [])
	{
		$defaultOptions = [
			'auto_reload' => true,
			'cache' => new CappuccinoCache(),
			'debug' => tw()->getPreferences()['developer']['debugMode'] ?? false
		];

		parent::__construct(array_merge($defaultOptions, $options), new CappuccinoLoader());

		$this->addGlobal('site', new Site());
		$this->addGlobal('template', new Template());
		$this->addGlobal('tw', tw());

		$this->addExtension(new CappuccinoFunctions());
		$this->addPath(PUBLIC_DIR . '/tw/view', 'tw');
	}

	/**
	 * Returns TRUE if the given template exists.
	 *
	 * @param string $template
	 *
	 * @return bool
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public function exists(string $template): bool
	{
		return $this->cappuccino->getLoader()->exists($template);
	}

}
