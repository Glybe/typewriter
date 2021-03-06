<?php
declare(strict_types=1);

namespace TypeWriter\Twig;

use Twig\Loader\FilesystemLoader;
use function array_unique;
use function get_stylesheet_directory;
use function get_template_directory;
use function is_file;
use function str_ends_with;
use function str_starts_with;
use function TypeWriter\tw;

/**
 * Class TwigLoader
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Twig
 * @since 1.0.0
 */
final class TwigLoader extends FilesystemLoader
{

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    protected function findTemplate(string $name, bool $throw = true): ?string
    {
        if (!str_ends_with($name, '.twig')) {
            $name .= '.twig';
        }

        if (!str_starts_with($name, '@') && tw()->getState()->get('tw.is-wp-initialized', false)) {
            $themeDirectories = array_unique([
                get_stylesheet_directory(),
                get_template_directory()
            ]);

            foreach ($themeDirectories as $directory) {
                if (is_file($templateFile = $directory . '/template/' . $name)) {
                    return $templateFile;
                }
            }
        }

        return parent::findTemplate($name, $throw);
    }

}
