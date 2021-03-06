<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\TemplateException;
use TypeWriter\Util\Sandbox;
use function extract;
use function file_get_contents;
use function get_body_class;
use function get_page_template_slug;
use function get_theme_mod;
use function get_theme_mods;
use function is_file;
use function locate_template;
use function TypeWriter\twig;
use const EXTR_OVERWRITE;

/**
 * Class Template
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class Template
{

    private static array $icons = [];

    /**
     * Gets the body classes. If extraClasses is specified, they're added.
     *
     * @param string[] $extraClasses
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see get_body_class()
     */
    public static function bodyClasses(array $extraClasses = []): array
    {
        return get_body_class($extraClasses);
    }

    /**
     * Gets a theme modification.
     *
     * @param string $id
     * @param mixed $defaultValue
     *
     * @return mixed
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function modification(string $id, mixed $defaultValue): mixed
    {
        return get_theme_mod($id, $defaultValue);
    }

    /**
     * Gets all theme modifications.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function modifications(): array
    {
        return get_theme_mods();
    }

    /**
     * Renders and gets the given template. This method exposes the given context to that template.
     *
     * @hook tw.template.part (string $template, array $context): void
     *
     * @param string $template
     * @param array $context
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function part(string $template, array $context = []): string
    {
        Hooks::doAction('tw.template.part', $template, $context);

        $templateFile = "template/part/{$template}.php";
        $templateFile = locate_template($templateFile);

        if (is_file($templateFile)) {
            return Sandbox::render($templateFile, $context);
        } else if (twig()->exists($template)) {
            return twig()->render($template, $context);
        } else {
            throw new TemplateException(sprintf('Could not find template part "%s".', $template), TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
        }
    }

    /**
     * Renders the given template. This method exposes the given context to that template.
     *
     * @hook tw.template.print-part (string $template, array $context): void
     *
     * @param string $template
     * @param array $context
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function printPart(string $template, array $context = []): void
    {
        Hooks::doAction('tw.template.print-part', $template, $context);

        $templateFile = 'template/part/' . $template . '.php';
        $templateFile = locate_template($templateFile);

        if (is_file($templateFile)) {
            extract($context, EXTR_OVERWRITE);

            require $templateFile;
        } else if (twig()->exists($template)) {
            echo twig()->render($template, $context);
        } else {
            throw new TemplateException(sprintf('Could not find template part "%s".', $template), TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
        }
    }

    /**
     * Renders the footer template.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function renderFooter(): string
    {
        $twig = twig();

        if ($twig->exists('@theme/global/footer.twig')) {
            return $twig->render('@theme/global/footer.twig');
        } else if (is_file($footerPath = Dependencies::themePath('footer.php'))) {
            return Sandbox::render($footerPath);
        } else {
            throw new TemplateException('The footer template was not found. Create a template/global/footer.twig or footer.php file in your theme.', TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
        }
    }

    /**
     * Renders the header template.
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function renderHeader(): string
    {
        $twig = twig();

        if ($twig->exists('@theme/global/header.twig')) {
            return $twig->render('@theme/global/header.twig');
        } else if (is_file($headerPath = Dependencies::themePath('header.php'))) {
            return Sandbox::render($headerPath);
        } else {
            throw new TemplateException('The header template was not found. Create a template/global/header.twig or header.php file in your theme.', TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
        }
    }

    /**
     * Renders the svg code of the given icon.
     *
     * @param string $style
     * @param string $icon
     *
     * @return string
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function renderIcon(string $style, string $icon): string
    {
        $key = "{$style}_{$icon}";

        return self::$icons[$key] ??= file_get_contents(Dependencies::themePath("resource/icon/{$style}/{$icon}.svg"));
    }

    /**
     * Gets the template slug.
     *
     * @return string|null
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @see get_page_template_slug()
     */
    public static function slug(): ?string
    {
        return get_page_template_slug() ?: null;
    }

}
