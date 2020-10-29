<?php
declare(strict_types=1);

namespace TypeWriter\Module\Core;

use Columba\Http\ResponseCode;
use TypeWriter\Facade\Dependencies;
use TypeWriter\Facade\Hooks;
use TypeWriter\Module\Module;
use WP_Site_Health;
use function class_exists;
use function file_get_contents;
use function header;
use function http_response_code;
use function implode;
use function load_plugin_textdomain;
use function str_replace;
use function TypeWriter\tw;
use function wp_set_script_translations;
use const TypeWriter\ROOT;
use const TypeWriter\WP_DIR;

/**
 * Class AdminModule
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Core
 * @since 1.0.0
 */
final class AdminModule extends Module
{

    /**
     * AdminModule constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adds basic TypeWriter features to the WordPress Admin.');
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onInitialize(): void
    {
        if (($_SERVER['REQUEST_URI'] ?? '/') === '/wp/wp-admin') {
            http_response_code(ResponseCode::SEE_OTHER);
            header('Location: /wp/wp-admin/index.php');
            die;
        }

        $this->patchWpSiteHealthClass();

        Hooks::action('admin_enqueue_scripts', [$this, 'onAdminEnqueueScripts']);
        Hooks::action('init', [$this, 'onInit']);
        Hooks::action('in_admin_footer', [$this, 'onInAdminFooter']);

        Hooks::filter('user_has_cap', function (array $caps): array {
            if (!(tw()->getPreferences()['security']['lockDownMode'] ?? false)) {
                return $caps;
            }

            $isDebug = tw()->isDebugMode();

            /*
             * These capabilities are only available when on debug mode, this is
             * so that our customer cannot break their site.
             */
            $caps['update_core'] = ($caps['update_core'] ?? false) && $isDebug;
            $caps['install_languages'] = ($caps['install_languages'] ?? false) && $isDebug;
            $caps['delete_plugins'] = ($caps['delete_plugins'] ?? false) && $isDebug;
            $caps['edit_plugins'] = ($caps['edit_plugins'] ?? false) && $isDebug;
            $caps['install_plugins'] = ($caps['install_plugins'] ?? false) && $isDebug;
            $caps['update_plugins'] = ($caps['update_plugins'] ?? false) && $isDebug;
            $caps['delete_themes'] = ($caps['delete_themes'] ?? false) && $isDebug;
            $caps['edit_themes'] = ($caps['edit_themes'] ?? false) && $isDebug;
            $caps['install_themes'] = ($caps['install_themes'] ?? false) && $isDebug;
            $caps['update_themes'] = ($caps['update_themes'] ?? false) && $isDebug;

            return $caps;
        });
    }

    /**
     * Invoked on admin_enqueue_scripts action hook.
     * Adds the TypeWriter JS and CSS files to the WordPress Admin.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onAdminEnqueueScripts(): void
    {
        Dependencies::registerStyle('tw', home_url('/tw/dist/admin.css'));
        Dependencies::registerScript('tw', home_url('/tw/dist/admin.js'), ['wp-i18n', 'wp-mediaelement']);

        wp_set_script_translations('tw', 'tw', ROOT . '/resource/language');

        Dependencies::enqueueStyle('tw');
        Dependencies::enqueueScript('tw');
    }

    /**
     * Invoked on init action hook.
     * Adds our translations to WordPress.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onInit(): void
    {
        load_plugin_textdomain('tw', false, '../../../resource/language');
    }

    /**
     * Invoked on in_admin_footer action hook.
     * Adds the TypeWriter feature scripts.
     *
     * @hook tw.admin-scripts.body (array $scripts): array
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     * @internal
     */
    public final function onInAdminFooter(): void
    {
        $scripts = implode(PHP_EOL, Hooks::applyFilters('tw.admin-scripts.body', []));

        echo <<<CODE
		<script type="text/javascript">
		window.addEventListener("load", function () { 
		{$scripts}
		});
		</script>
		CODE;
    }

    /**
     * Patches the {@see WP_Site_Health} class to work with our custom database.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function patchWpSiteHealthClass(): void
    {
        if (class_exists(WP_Site_Health::class)) {
            return;
        }

        $code = file_get_contents(WP_DIR . '/wp-admin/includes/class-wp-site-health.php');

        $code = str_replace(
            '$mysql_server_type = mysqli_get_server_info( $wpdb->dbh );',
            '$mysql_server_type = $wpdb->dbh->attribute(PDO::ATTR_SERVER_VERSION);',
            $code
        );

        eval(mb_substr($code, 5));
    }

}
