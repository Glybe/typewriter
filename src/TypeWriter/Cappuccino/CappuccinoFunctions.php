<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino;

use Cappuccino\CappuccinoFunction;
use Cappuccino\Extension\AbstractExtension;

/**
 * Class CappuccinoFunctions
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino
 * @since 1.0.0
 */
final class CappuccinoFunctions extends AbstractExtension
{

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getFunctions(): array
	{
		return [
			new CappuccinoFunction('get_footer', 'get_footer'),
			new CappuccinoFunction('get_header', 'get_header')
		];
	}

}
