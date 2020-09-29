<?php
declare(strict_types=1);

namespace TypeWriter\Facade;

use Columba\Util\StringUtil;
use function add_menu_page;
use function add_submenu_page;
use function remove_menu_page;
use function remove_submenu_page;
use function TypeWriter\tw;

/**
 * Class AdminMenu
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Facade
 * @since 1.0.0
 */
final class AdminMenu
{

    /**
     * Adds a menu page to wp-admin.
     *
     * @param string $icon
     * @param string $title
     * @param callable $fn
     * @param string $capability
     * @param int|null $position
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function addPage(string $icon, string $title, callable $fn, string $capability = 'administrator', ?int $position = null): void
    {
        add_menu_page($title, $title, $capability, StringUtil::slugify($title), $fn, $icon, $position);
    }

    /**
     * Adds a submenu page to wp-admin.
     *
     * @param string $parentSlug
     * @param string $title
     * @param callable $fn
     * @param string $capability
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function addSubPage(string $parentSlug, string $title, callable $fn, string $capability = 'administrator'): void
    {
        add_submenu_page($parentSlug, $title, $title, $capability, $parentSlug . '-' . StringUtil::slugify($title), $fn);
    }

    /**
     * Removes a menu page from wp-admin.
     *
     * @param string $slug
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function removePage(string $slug): void
    {
        remove_menu_page($slug);
    }

    /**
     * Removes a submenu page from wp-admin.
     *
     * @param string $parentSlug
     * @param string $slug
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function removeSubPage(string $parentSlug, string $slug): void
    {
        remove_submenu_page($parentSlug, $slug);
    }

    /**
     * Higher order function that returns a Twig render callable.
     *
     * @param string $template
     * @param callable|null $contextGenerator
     *
     * @return callable
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public static function twig(string $template, ?callable $contextGenerator = null): callable
    {
        return function () use ($template, $contextGenerator): void {
            $context = $contextGenerator !== null ? $contextGenerator() : [];

            echo tw()->getTwig()->render($template, $context);
        };
    }

}
