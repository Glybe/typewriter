<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use Composer\InstalledVersions;
use TypeWriter\Facade\AdminMenu;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use TypeWriter\Util\BrandingUtil;
use function __;
use function array_map;
use function array_unique;
use function TypeWriter\tw;

/**
 * Class AdminMenuModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class AdminMenuModule extends Module
{

    /**
     * AdminMenuModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Creates the TypeWriter admin menu and various options.');
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        Hooks::action('admin_menu', [$this, 'onAdminMenu']);
    }

    /**
     * Invoked on admin_menu action hook.
     * Adds the TypeWriter menu entry.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminMenu(): void
    {
        $hooks = [];
        $hooks[] = AdminMenu::addPage('dashicons-edit', __('TypeWriter', 'tw'), AdminMenu::twig('@tw/admin/about', [$this, 'generateAboutContext']));
        $hooks[] = AdminMenu::addSubPage('typewriter', __('Roles & Permissions', 'tw'), AdminMenu::twig('@tw/admin/roles-and-permissions'));
        $hooks[] = AdminMenu::addSubPage('typewriter', __('Settings', 'tw'), AdminMenu::twig('@tw/admin/settings'));

        $hooks = array_map(fn(string $hook): string => 'load-' . $hook, $hooks);
        $hooks = array_unique($hooks);

        Hooks::actions($hooks, function (): void {
            Dependencies::enqueueScript('site-health');
            Dependencies::enqueueStyle('site-health');

            Hooks::filter('admin_body_class', fn(string $classes): string => $classes . ' site-health');
        });
    }

    /**
     * Generates the context for the about page.
     *
     * @return array
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function generateAboutContext(): array
    {
        $dependencies = [];
        $installed = InstalledVersions::getRawData()['versions'];

        foreach ($installed as $id => $dependency) {
            if ($id === 'basmilius/typewriter') {
                continue;
            }

            $dependency['name'] = $id;
            $dependencies[] = $dependency;
        }

        return [
            'logo' => BrandingUtil::get('logo'),
            'logo_url' => BrandingUtil::get('logo_url'),
            'version' => InstalledVersions::getPrettyVersion('basmilius/typewriter'),
            'dependencies' => $dependencies,
            'modules' => tw()->getModules(),
            'plugins' => get_plugins()
        ];
    }

}
