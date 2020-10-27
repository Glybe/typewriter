<?php
declare(strict_types=1);

namespace TypeWriter\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Twig\TokenParser\ControllerTokenParser;
use TypeWriter\Twig\TokenParser\FooterTokenParser;
use TypeWriter\Twig\TokenParser\HeaderTokenParser;
use function __;
use function call_user_func;
use function Columba\Util\dump;
use function Columba\Util\dumpDie;
use function Columba\Util\pre;
use function Columba\Util\preDie;
use function func_get_args;
use function home_url;

/**
 * Class TwigFunctions
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig
 * @since 1.0.0
 */
final class TwigFunctions extends AbstractExtension
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getFunctions(): array
    {
        return [
            new TwigFunction('dump', fn(...$args) => dump(...$args)),
            new TwigFunction('dumpDie', fn(...$args) => dumpDie(...$args)),
            new TwigFunction('pre', fn(...$args) => pre(...$args)),
            new TwigFunction('preDie', fn(...$args) => preDie(...$args)),

            new TwigFunction('applyFilters', [Hooks::class, 'applyFilters'], ['is_safe' => ['html']]),
            new TwigFunction('doAction', [Hooks::class, 'doAction']),
            new TwigFunction('t', fn(string $text, string $domain = 'tw') => __($text, $domain)),
            new TwigFunction('themeUri', fn(string $path): string => Dependencies::themeUri($path)),
            new TwigFunction('url', fn(string $path = ''): string => home_url($path)),

            new TwigFunction('wp', fn() => call_user_func(...func_get_args()))
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
