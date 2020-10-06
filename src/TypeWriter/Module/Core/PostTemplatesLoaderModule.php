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

namespace TypeWriter\Module\Core;

use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use TypeWriter\Util\DocUtil;
use WP_Post;
use WP_Theme;
use function array_merge;
use function array_shift;
use function array_unique;
use function implode;
use function in_array;
use function is_dir;
use function scandir;
use function substr;

/**
 * Class PostTemplatesLoaderModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class PostTemplatesLoaderModule extends Module
{

    private static array $ignoredTemplateFiles = [
        'archive.twig', 'archive.php',
        'default.twig', 'default.php',
        'single.twig', 'single.php'
    ];

    /**
     * PostTemplatesLoaderModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Loads additional templates inside themes.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::filter('theme_templates', [$this, 'onThemeTemplates']);
    }

    /**
     * Invoked on theme_templates filter hook.
     * Adds templates from a theme's template directory to the template selector.
     *
     * @param array $templates
     * @param WP_Theme $theme
     * @param WP_Post|null $post
     * @param string $postType
     *
     * @return string[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     * @noinspection PhpUnusedParameterInspection
     */
    public final function onThemeTemplates(array $templates, WP_Theme $theme, ?WP_Post $post, string $postType): array
    {
        $themeDirectories = array_unique([
            $theme->get_stylesheet_directory(),
            $theme->get_template_directory()
        ]);

        foreach ($themeDirectories as $directory) {
            $templatesDirectory = implode('/', [$directory, 'template', $postType]);

            if (!is_dir($templatesDirectory)) {
                continue;
            }

            $files = scandir($templatesDirectory);
            array_shift($files);
            array_shift($files);

            $themeTemplates = [];

            foreach ($files as $file) {
                if (substr($file, -4) !== '.php' && substr($file, -5) !== '.twig') {
                    continue;
                }

                if (in_array($file, self::$ignoredTemplateFiles)) {
                    continue;
                }

                $path = "template/{$postType}/{$file}";
                $props = DocUtil::getProperties(Dependencies::themePath($path));

                $themeTemplates[$path] = $props['template'] ?? $file;
            }

            $templates = array_merge($templates, $themeTemplates);
        }

        return $templates;
    }

}
