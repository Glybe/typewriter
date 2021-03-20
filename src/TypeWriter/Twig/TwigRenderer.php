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

namespace TypeWriter\Twig;

use Columba\Router\Renderer\AbstractRenderer;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use TypeWriter\Facade\Attachment;
use TypeWriter\Facade\Site;
use TypeWriter\Facade\Template;
use TypeWriter\Structure\Menu\Menus;
use function array_merge;
use function TypeWriter\tw;
use const TypeWriter\RESOURCE_DIR;

/**
 * Class TwigRenderer
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig
 * @since 1.0.0
 */
final class TwigRenderer extends AbstractRenderer
{

    private Twig $twig;
    private TwigLoader $loader;
    private array $options;

    /**
     * TwigRenderer constructor.
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
            'cache' => new TwigCache(),
            'debug' => tw()->isDebugMode()
        ];

        $this->loader = new TwigLoader([]);
        $this->options = array_merge($defaultOptions, $options);
        $this->twig = new Twig($this->loader, $this->options);

        $this->addGlobal('attachment', new Attachment());
        $this->addGlobal('menus', new Menus());
        $this->addGlobal('site', new Site());
        $this->addGlobal('template', new Template());
        $this->addGlobal('tw', tw());

        $this->addExtension(new TwigFunctions());
        $this->addPath(RESOURCE_DIR . '/view', 'tw');
    }

    /**
     * Adds the given extension.
     *
     * @param ExtensionInterface $extension
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addExtension(ExtensionInterface $extension): void
    {
        $this->twig->addExtension($extension);
    }

    /**
     * Adds a global.
     *
     * @param string $name
     * @param mixed $value
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addGlobal(string $name, mixed $value): void
    {
        $this->twig->addGlobal($name, $value);
    }

    /**
     * Adds a view path.
     *
     * @param string $path
     * @param string $namespace
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function addPath(string $path, string $namespace = FilesystemLoader::MAIN_NAMESPACE): void
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
        return $this->twig->getLoader()->exists($template);
    }

    /**
     * Gets the Twig loader instance.
     *
     * @return TwigLoader
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getLoader(): TwigLoader
    {
        return $this->loader;
    }

    /**
     * Gets the Twig instance.
     *
     * @return Twig
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function getTwig(): Twig
    {
        return $this->twig;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function render(string $template, array $context = []): string
    {
        try {
            return $this->twig->render($template, $context);
        } catch (LoaderError | RuntimeError | SyntaxError $err) {
            throw $this->error($err);
        }
    }

}
