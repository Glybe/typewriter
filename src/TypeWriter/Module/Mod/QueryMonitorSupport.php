<?php
/**
 * Copyright (c) 2019-2020 - Bas Milius <bas@mili.us>
 *
 * This file is part of TypeWriter, a base framework for WordPress.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace TypeWriter\Module\Mod;

use Columba\Database\Query\Statement;
use PDO;
use QM_Backtrace;
use QM_Collector;
use QM_Collector_DB_Queries;
use QM_Collector_Environment;
use QM_Collectors;
use QM_Util;
use TypeWriter\Facade\Hooks;
use TypeWriter\Facade\Plugin;
use TypeWriter\Module\Module;
use WP_Error;
use function array_combine;
use function array_keys;
use function array_map;
use function define;
use function defined;
use function error_reporting;
use function explode;
use function function_exists;
use function get_loaded_extensions;
use function implode;
use function ini_get;
use function method_exists;
use function php_sapi_name;
use function php_uname;
use function phpversion;
use function reset;
use function sprintf;
use function TypeWriter\tw;
use function version_compare;
use function wp_unslash;

/**
 * Class QueryMonitorSupport
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Module\Mod
 * @since 1.0.0
 */
final class QueryMonitorSupport extends Module
{

    /**
     * QueryMonitorSupport constructor.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct('Adds support for the Query Monitor plugin.');
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function onInitialize(): void
    {
        if (!Plugin::active('query-monitor/query-monitor.php')) {
            return;
        }

        $this->initializeQueryMonitor();

        Hooks::filter('qm/collectors', [$this, 'onCollectors'], 1000);
        Hooks::filter('qm/show_extended_query_prompt', fn(): bool => false);
        Hooks::filter('tw.database.after-query', [$this, 'onAfterQuery']);
    }

    /**
     * Invoked on the tw.database.after-query action hook.
     * Adds backtrace information to the query.
     *
     * @param string $query
     * @param int $index
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onAfterQuery(string $query, int $index): void
    {
        global $wpdb;

        if (!SAVEQUERIES)
            return;

        $trace = new QM_Backtrace();
        $trace->ignore(1);
        $trace->ignore(1);
        $wpdb->queries[$index]['trace'] = $trace;

        if (!isset($wpdb->queries[$index][0])) {
            $wpdb->queries[$index][0] = $query;
        }

        if (!isset($wpdb->queries[$index][3])) {
            $wpdb->queries[$index][3] = $wpdb->time_start;
        }

        if (!empty($wpdb->last_error)) {
            $code = tw()->getDatabase()->getPdo()->errorInfo()[0] ?? 'qmdb';
            $wpdb->queries[$index]['result'] = new WP_Error($code, $wpdb->last_error);
        } else {
            /** @var Statement $result */
            $result = $wpdb->{'result'};

            $wpdb->queries[$index]['result'] = $result->rowCount();
        }
    }

    /**
     * Invoked on the qm/collectors filter hook.
     * Adds our custom logic to Query Monitor.
     *
     * @param array $collectors
     *
     * @return QM_Collector[]
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function onCollectors(array $collectors): array
    {
        $collectors['environment'] = $this->makeEnvironmentClass();

        return $collectors;
    }

    /**
     * Initializes and mimics the Query Monitor db.php drop-in.
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function initializeQueryMonitor(): void
    {
        global $wpdb;

        $pluginDir = Plugin::dir('query-monitor');

        require_once $pluginDir . '/classes/Plugin.php';
        require_once $pluginDir . '/classes/Backtrace.php';

        if (!defined('SAVEQUERIES')) {
            define('SAVEQUERIES', true);
        }

        $vars = ['max_execution_time', 'memory_limit', 'upload_max_filesize', 'post_max_size', 'display_errors', 'log_errors'];

        $wpdb->{'qm_php_vars'} = [];

        foreach ($vars as $var) {
            $wpdb->{'qm_php_vars'}[$var] = ini_get($var);
        }
    }

    /**
     * Overrides the default Query Monitor environment tab.
     *
     * @return QM_Collector
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function makeEnvironmentClass(): QM_Collector
    {
        return new class extends QM_Collector_Environment {

            /**
             * Overrides the process method of the default environment collector.
             *
             * @author Bas Milius <bas@mili.us>
             * @since 1.0.0
             * @see QM_Collector_Environment::process()
             */
            public function process()
            {
                global $wp_version;

                $mysqlVariables = [
                    'key_buffer_size' => true,
                    'max_allowed_packet' => false,
                    'max_connections' => false,
                    'query_cache_limit' => true,
                    'query_cache_size' => true,
                    'query_cache_type' => 'ON'
                ];

                /** @var QM_Collector_DB_Queries $dbq */
                $dbq = QM_Collectors::get('db_queries');

                if ($dbq) {
                    foreach ($dbq->db_objects as $id => $db) {
                        $server = method_exists($db, 'db_version') ? $db->db_version() : null;

                        $extension = 'Columba Database';
                        $variables = $db->get_results("SHOW VARIABLES WHERE Variable_name IN ('" . implode("', '", array_keys($mysqlVariables)) . "')");

                        $client = tw()->getDatabase()->getPdo()->getAttribute(PDO::ATTR_CLIENT_VERSION);

                        if ($client) {
                            $clientVersion = implode('.', QM_Util::get_client_version($client));
                            $clientVersion = sprintf('%s (%s)', $client, $clientVersion);
                        } else {
                            $clientVersion = null;
                        }

                        $info = [
                            'server-version' => $server,
                            'extension' => $extension,
                            'client-version' => $clientVersion,
                            'user' => $db->dbuser,
                            'host' => $db->dbhost,
                            'database' => $db->dbname,
                        ];

                        $this->data['db'][$id] = [
                            'info' => $info,
                            'vars' => $mysqlVariables,
                            'variables' => $variables,
                        ];
                    }
                }

                $this->data['php']['version'] = phpversion();
                $this->data['php']['sapi'] = php_sapi_name();
                $this->data['php']['user'] = QM_Collector_Environment::get_current_user();
                $this->data['php']['old'] = version_compare($this->data['php']['version'], '7.2', '<');

                foreach ($this->php_vars as $setting) {
                    $this->data['php']['variables'][$setting]['after'] = ini_get($setting);
                }

                $sortFlags = defined('SORT_CLAG_CASE') ? SORT_STRING | SORT_FLAG_CASE : SORT_STRING;

                if (is_callable('get_loaded_extensions')) {
                    $extensions = get_loaded_extensions();
                    sort($extensions, $sortFlags);
                    $this->data['php']['extensions'] = array_combine($extensions, array_map([$this, 'get_extension_version'], $extensions));
                } else {
                    $this->data['php']['extensions'] = [];
                }

                $this->data['php']['error_reporting'] = error_reporting();
                $this->data['php']['error_levels'] = QM_Collector_Environment::get_error_levels($this->data['php']['error_reporting']);

                $this->data['wp']['version'] = $wp_version;
                $this->data['wp']['constants'] = apply_filters('qm/environment-constants', [
                    'WP_DEBUG' => QM_Collector_Environment::format_bool_constant('WP_DEBUG'),
                    'WP_DEBUG_DISPLAY' => QM_Collector_Environment::format_bool_constant('WP_DEBUG_DISPLAY'),
                    'WP_DEBUG_LOG' => QM_Collector_Environment::format_bool_constant('WP_DEBUG_LOG'),
                    'SCRIPT_DEBUG' => QM_Collector_Environment::format_bool_constant('SCRIPT_DEBUG'),
                    'WP_CACHE' => QM_Collector_Environment::format_bool_constant('WP_CACHE'),
                    'CONCATENATE_SCRIPTS' => QM_Collector_Environment::format_bool_constant('CONCATENATE_SCRIPTS'),
                    'COMPRESS_SCRIPTS' => QM_Collector_Environment::format_bool_constant('COMPRESS_SCRIPTS'),
                    'COMPRESS_CSS' => QM_Collector_Environment::format_bool_constant('COMPRESS_CSS'),
                    'WP_LOCAL_DEV' => QM_Collector_Environment::format_bool_constant('WP_LOCAL_DEV'),
                ]);

                if (is_multisite()) {
                    $this->data['wp']['constants']['SUNRISE'] = QM_Collector_Environment::format_bool_constant('SUNRISE');
                }

                if (isset($_SERVER['SERVER_SOFTWARE'])) {
                    $server = explode(' ', wp_unslash($_SERVER['SERVER_SOFTWARE']));
                    $server = explode('/', reset($server));
                } else {
                    $server = [''];
                }

                $address = isset($_SERVER['SERVER_ADDR']) ? wp_unslash($_SERVER['SERVER_ADDR']) : null;
                $serverVersion = isset($server[1]) ? $server[1] : null;

                $this->data['server'] = [
                    'name' => $server[0],
                    'version' => $serverVersion,
                    'address' => $address,
                    'host' => null,
                    'OS' => null,
                ];

                if (function_exists('php_uname')) {
                    $this->data['server']['host'] = php_uname('n');
                    $this->data['server']['OS'] = php_uname('s') . ' ' . php_uname('r');
                }
            }

        };
    }

}
