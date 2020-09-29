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

namespace TypeWriter\Database;

use Columba\Database\Connection\Connection;
use Columba\Database\Connection\MySqlConnection;
use Columba\Database\Connector\Connector;
use Columba\Database\Connector\MySqlConnector;
use Columba\Database\Db;
use PDO;
use PDOException;
use TypeWriter\Facade\Hooks;
use wpdb;
use function __;
use function addslashes;
use function array_change_key_case;
use function array_filter;
use function defined;
use function error_log;
use function explode;
use function htmlspecialchars;
use function implode;
use function in_array;
use function is_multisite;
use function is_string;
use function preg_match;
use function preg_replace;
use function printf;
use function sprintf;
use function TypeWriter\tw;
use function wp_die;
use function wp_load_translations_early;
use const ENT_QUOTES;

/**
 * Class Database
 *
 * @author Bas Milius <bas@mili.us>
 * @package TypeWriter\Database
 * @since 1.0.0
 */
final class Database extends wpdb
{

    private Connection $connection;
    private Connector $connector;

    /**
     * Database constructor.
     *
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $host
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function __construct(string $user, string $password, string $database, string $host)
    {
        $this->connector = new MySqlConnector($host, $database, $user, $password);
        $this->connection = $this->dbh = Db::create(MySqlConnection::class, $this->connector, 'default', false);

        tw()->setDatabase($this->connection);

        parent::__construct($user, $password, $database, $host);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function _real_escape($string)
    {
        if (is_string($string))
            $string = addslashes($string);

        return $this->add_placeholder_escape($string);
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function db_connect($allow_bail = true): bool
    {
        $this->connection->connect();

        $this->init_charset();
        $this->set_charset(null);

        $this->ready = true;
        $this->set_sql_mode();

        return true;
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function db_version(): string
    {
        return preg_replace('/[^0-9.].*/', '', $this->connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION));
    }

    /**
     * {@inheritDoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public function print_error($str = '')
    {
        global $EZSQL_ERROR;

        if (empty($str))
            $str = $this->connection->getPdo()->errorInfo()[2] ?? 'Unknown database error.';

        $EZSQL_ERROR[] = [
            'query' => $this->last_query,
            'error_str' => $str,
        ];

        if ($this->suppress_errors)
            return false;

        wp_load_translations_early();

        if (($caller = $this->get_caller()))
            $errorMessage = sprintf(__('WordPress database error %1$s for query %2$s made by %3$s'), $str, $this->last_query, $caller);
        else
            $errorMessage = sprintf(__('WordPress database error %1$s for query %2$s'), $str, $this->last_query);

        error_log($errorMessage);

        if (!$this->show_errors)
            return false;

        if (is_multisite()) {
            $message = sprintf("%s [%s]\n%s\n", __('WordPress database error:'), $str, $this->last_query);

            if (defined('ERRORLOGFILE'))
                error_log($message, 3, ERRORLOGFILE);

            if (defined('DIEONDBERROR') && DIEONDBERROR)
                wp_die($message);
        } else {
            $str = htmlspecialchars($str, ENT_QUOTES);
            $query = htmlspecialchars($this->last_query, ENT_QUOTES);

            printf('<div id="error"><p class="wpdberror"><strong>%s</strong> [%s]<br /><code>%s</code></p></div>', __('WordPress database error:'), $str, $query);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @hook tw.database.after-query (string $query, int $queryId): void
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function query($query)
    {
        if (!$this->ready) {
            $this->check_current_query = true;

            return false;
        }

        $query = (string)Hooks::applyFilters('query', $query);

        $this->flush();

        $this->func_call = "\$db->query(\"$query\")";

        if ($this->check_current_query && !$this->check_ascii($query)) {
            $strippedQuery = $this->strip_invalid_text_from_query($query);
            $this->flush();

            if ($strippedQuery !== $query) {
                $this->insert_id = 0;

                return false;
            }
        }

        $this->check_current_query = true;
        $this->last_query = $query;

        $this->doQuery($query);

        Hooks::doAction('tw.database.after-query', $query, $this->num_queries - 1);

        $errorCode = $this->connection->getPdo()->errorInfo()[1] ?? 0;

        if ($errorCode === 2006) {
            if ($this->check_connection()) {
                $this->doQuery($query);
            } else {
                $this->insert_id = 0;

                return false;
            }
        }

        $this->last_error = $this->connection->getPdo()->errorInfo()[2] ?? null;

        if ($this->last_error) {
            if ($this->insert_id && preg_match('/^\s*(insert|replace)\s/i', $query))
                $this->insert_id = 0;

            $this->print_error();

            return false;
        }

        if (preg_match('/^\s*(create|alter|truncate|drop)\s/i', $query)) {
            return $this->result;
        } else if (preg_match('/^\s*(insert|delete|update|replace)\s/i', $query)) {
            $this->rows_affected = $this->result->rowCount();
            $this->insert_id = $this->connection->lastInsertIdInteger();

            return $this->rows_affected;
        } else {
            $numRows = 0;

            while ($row = $this->result->getPdoStatement()->fetch(PDO::FETCH_OBJ)) {
                $this->last_result[$numRows] = $row;
                $numRows++;
            }

            $this->num_rows = $numRows;

            return $numRows;
        }
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function set_charset($dbh, $charset = null, $collate = null): void
    {
        if (!isset($charset))
            $charset = $this->charset;

        if (!isset($collate))
            $collate = $this->collate;

        if (!empty($collate))
            $this->connection->prepare(sprintf("SET NAMES '%s' COLLATE '%s'", $charset, $collate))->run();
        else
            $this->connection->prepare(sprintf("SET NAMES '%s'", $charset))->run();
    }

    /**
     * {@inheritdoc}
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    public final function set_sql_mode($modes = [])
    {
        if (empty($modes)) {
            $smt = $this->connection->prepare('SELECT @@SESSION.sql_mode');
            $smt->run();
            $res = $smt->getPdoStatement()->fetch(PDO::FETCH_NUM);

            $modes = array_filter(explode(',', $res[0]), fn($val) => !empty($val));
        }

        $modes = array_change_key_case($modes, CASE_UPPER);
        $incompatibleModes = (array)Hooks::applyFilters('incompatible_sql_modes', $this->incompatible_modes);

        foreach ($modes as $i => $mode)
            if (in_array($mode, $incompatibleModes))
                unset($modes[$i]);

        $modesStr = implode(',', $modes);

        $this->connection->prepare("SET SESSION sql_mode = '$modesStr'")->run();
    }

    /**
     * Performs the actual query.
     *
     * @param string $query
     *
     * @author Bas Milius <bas@mili.us>
     * @since 1.0.0
     */
    private function doQuery(string $query): void
    {
        if (defined('SAVEQUERIES') && SAVEQUERIES)
            $this->timer_start();

        try {
            $this->result = $this->connection->prepare($query);
            $this->result->run();
        } catch (PDOException $err) {
        }

        if (defined('SAVEQUERIES') && SAVEQUERIES)
            $this->queries[$this->num_queries] = [$query, $this->timer_stop(), $this->get_caller()];

        $this->num_queries++;
    }

}
