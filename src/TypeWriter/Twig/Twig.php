<?php
declare(strict_types=1);

namespace TypeWriter\Twig;

use Twig\Environment as TwigEnvironment;
use TypeWriter\Facade\Attachment;
use TypeWriter\Facade\Site;
use TypeWriter\Facade\Template;
use TypeWriter\Structure\Menu\Menus;
use function TypeWriter\tw;
use const TypeWriter\RESOURCE_DIR;

/**
 * Class Twig
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig
 * @since 1.0.0
 */
final class Twig extends TwigEnvironment
{

    private TwigCache $cache;
    private TwigLoader $loader;

    /**
     * Twig constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->cache = new TwigCache();
        $this->loader = new TwigLoader();

        parent::__construct($this->loader, [
            'auto_reload' => true,
            'cache' => $this->cache,
            'debug' => tw()->isDebugMode()
        ]);

        $this->addGlobal('attachment', new Attachment());
        $this->addGlobal('menus', new Menus());
        $this->addGlobal('site', new Site());
        $this->addGlobal('template', new Template());
        $this->addGlobal('tw', tw());

        $this->addExtension(new TwigFunctions());
        $this->addPath(RESOURCE_DIR . '/view', 'tw');
    }

    /**
     * Adds the given path to the twig loader.
     *
     * @param string $path
     * @param string $namespace
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addPath(string $path, string $namespace = TwigLoader::MAIN_NAMESPACE): void
    {
        $this->loader->addPath($path, $namespace);
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
    public final function exists(string $template): bool
    {
        return $this->loader->exists($template);
    }

}
