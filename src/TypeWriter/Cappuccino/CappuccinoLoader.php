<?php
declare(strict_types=1);

namespace TypeWriter\Cappuccino;

use Cappuccino\Loader\FilesystemLoader;
use function TypeWriter\tw;

/**
 * Class CappuccinoLoader
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Cappuccino
 * @since 1.0.0
 */
final class CappuccinoLoader extends FilesystemLoader
{

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	protected function findTemplate(string $name, bool $throw = true)
	{
		if (substr($name, 0, 1) !== '@' && tw()->getState()['tw.is-wp-initialized'])
		{
			$themeDirectories = array_unique([
				get_stylesheet_directory(),
				get_template_directory()
			]);

			foreach ($themeDirectories as $directory)
				if (is_file($templateFile = $directory . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $name))
					return $templateFile;
		}

		return parent::findTemplate($name, $throw);
	}

}
