<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use function filemtime;
use function is_file;
use function parse_url;
use function strstr;
use const TypeWriter\ROOT;

/**
 * Class DevelopmentModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class DevelopmentModule extends Module
{

    /**
     * DevelopmentModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Enables development features while in development mode.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onInitialize(): void
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
        $file = ROOT . '/public' . parse_url($src . '?ver=13', PHP_URL_PATH);
        $join = strstr($src, '?') !== false ? '&' : '?';

        if (!is_file($file)) {
            return $src;
        }

        return $src . $join . 'b=' . filemtime($file);
    }

}
