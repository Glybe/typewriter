<?php
declare(strict_types=1);

namespace TypeWriter\Twig;

use Raxos\Foundation\Util\Debug;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Site;
use TypeWriter\Facade\Template;
use TypeWriter\Twig\TokenParser\ControllerTokenParser;
use TypeWriter\Twig\TokenParser\FooterTokenParser;
use TypeWriter\Twig\TokenParser\HeaderTokenParser;
use TypeWriter\Twig\TokenParser\ScriptTokenParser;
use TypeWriter\Twig\TokenParser\StyleTokenParser;
use function call_user_func;
use function func_get_args;

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
            new TwigFunction('dump', fn(...$args) => Debug::dump(...$args)),
            new TwigFunction('dumpDie', fn(...$args) => Debug::dumpDie(...$args)),
            new TwigFunction('pre', fn(...$args) => Debug::print(...$args)),
            new TwigFunction('preDie', fn(...$args) => Debug::printDie(...$args)),

            new TwigFunction('applyFilters', [Hooks::class, 'applyFilters'], ['is_safe' => ['html']]),
            new TwigFunction('doAction', [Hooks::class, 'doAction']),
            new TwigFunction('icon', fn(string $style, string $icon) => Template::renderIcon($style, $icon), ['is_safe' => ['html']]),
            new TwigFunction('t', fn(string $text, ?string $language = null) => Site::translate($text, $language, 'default'), ['is_safe' => ['html']]),
            new TwigFunction('td', fn(string $domain, string $text, ?string $language = null) => Site::translate($text, $language, $domain), ['is_safe' => ['html']]),
            new TwigFunction('themeUri', fn(string $path): string => Dependencies::themeUri($path)),
            new TwigFunction('url', fn(string $path = ''): string => Site::url($path)),

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
            new HeaderTokenParser(),
            new ScriptTokenParser(),
            new StyleTokenParser()
        ];
    }

}
