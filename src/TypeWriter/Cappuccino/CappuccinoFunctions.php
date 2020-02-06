<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino;

use Cappuccino\CappuccinoFunction;
use Cappuccino\Extension\AbstractExtension;
use TypeWriter\Cappuccino\TokenParser\ControllerTokenParser;
use TypeWriter\Cappuccino\TokenParser\FooterTokenParser;
use TypeWriter\Cappuccino\TokenParser\HeaderTokenParser;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use function Columba\Util\dump;
use function Columba\Util\dumpDie;
use function Columba\Util\pre;
use function Columba\Util\preDie;

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
			new CappuccinoFunction('dump', '\\Columba\\Util\\dump'),
			new CappuccinoFunction('dumpDie', '\\Columba\\Util\\dumpDie'),
			new CappuccinoFunction('pre', '\\Columba\\Util\\pre'),
			new CappuccinoFunction('preDie', '\\Columba\\Util\\preDie'),

			new CappuccinoFunction('applyFilters', [Hooks::class, 'applyFilters'], ['is_safe' => ['html']]),
			new CappuccinoFunction('doAction', [Hooks::class, 'doAction']),
			new CappuccinoFunction('themeUri', fn(string $path) => Dependencies::themeUri($path)),

			new CappuccinoFunction('wp', fn() => call_user_func(...func_get_args()))
		];
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function getTokenParsers(): array
	{
		return [
			new ControllerTokenParser(),
			new FooterTokenParser(),
			new HeaderTokenParser()
		];
	}

}
