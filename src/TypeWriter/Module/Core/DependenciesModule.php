<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use JetBrains\PhpStorm\Pure;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function base_convert;
use function filemtime;
use function is_file;
use function parse_url;
use function strpos;
use function strstr;
use function strtolower;
use function urldecode;
use const PHP_URL_PATH;
use const TypeWriter\ROOT;

/**
 * Class DependenciesModule
 *
 * @author Bas Milius <bas@glybe.nl>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DependenciesModule extends Module
{

    /**
     * DependenciesModule constructor.
     *
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.0.0
     */
    #[Pure]
    public function __construct()
    {
        parent::__construct('Overrides dependency urls.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@glybe.nl>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('script_loader_src', [$this, 'onResourceSrc']);
        Hooks::filter('style_loader_src', [$this, 'onResourceSrc']);
    }

    /**
     * Invoked on script_loader_src and style_loader_src filter hooks.
     * Removes the version query param from the resource url. If we want versions, we'll use our own.
     *
     * @param string $src
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onResourceSrc(string $src): string
    {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }

        $src = urldecode($src);

        $file = ROOT . '/public' . parse_url($src . '?ver=13', PHP_URL_PATH);
        $join = strstr($src, '?') !== false ? '&' : '?';

        if (!is_file($file)) {
            return $src;
        }

        return $src . $join . 'b=' . strtolower(base_convert((string)(filemtime($file) ?: 0), 10, 16));
    }

}
