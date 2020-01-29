<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use TypeWriter\Error\TemplateException;
use function extract;
use function get_theme_mod;
use function get_theme_mods;
use function ob_get_clean;
use function ob_start;
use function TypeWriter\tw;
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

	/**
	 * Gets a theme modification.
	 *
	 * @param string $id
	 * @param mixed  $defaultValue
	 *
	 * @return mixed
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function modification(string $id, $defaultValue)
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
	 * @param string $template
	 * @param array  $context
	 *
	 * @return string
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function part(string $template, array $context = []): string
	{
		Hooks::doAction('tw.template.part', $template, $context);

		$templateFile = 'template/part/' . $template . '.php';
		$templateFile = locate_template($templateFile);

		if (is_file($templateFile))
		{
			extract($context, EXTR_OVERWRITE);
			ob_start();

			require $templateFile;

			return ob_get_clean();
		}
		else if (tw()->getCappuccino()->exists($template))
		{
			return tw()->getCappuccino()->render($template, $context);
		}
		else
		{
			throw new TemplateException(sprintf('Could not find template part "%s".', $template), TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
		}
	}

	/**
	 * Renders the given template. This method exposes the given context to that template.
	 *
	 * @param string $template
	 * @param array  $context
	 *
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public static function printPart(string $template, array $context = []): void
	{
		Hooks::doAction('tw.template.print-part', $template, $context);

		$templateFile = 'template/part/' . $template . '.php';
		$templateFile = locate_template($templateFile);

		if (is_file($templateFile))
		{
			extract($context, EXTR_OVERWRITE);

			require $templateFile;
		}
		else if (tw()->getCappuccino()->exists($template))
		{
			echo tw()->getCappuccino()->render($template, $context);
		}
		else
		{
			throw new TemplateException(sprintf('Could not find template part "%s".', $template), TemplateException::ERR_TEMPLATE_FILE_NOT_FOUND);
		}
	}

}
