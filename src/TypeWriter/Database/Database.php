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
use wpdb;
use function TypeWriter\tw;

/**
 * Class Database
 *
 * @author Bas Milius <bas@ideemedia.nl>
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
	public final function _real_escape($string)
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
	public final function db_connect($allow_bail = true): bool
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
	public final function db_version(): string
	{
		return preg_replace('/[^0-9.].*/', '', $this->connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION));
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function query($query)
	{
		if (!$this->ready)
		{
			$this->check_current_query = true;
			return false;
		}

		$query = (string)apply_filters('query', $query);

		$this->flush();

		$this->func_call = "\$db->query(\"$query\")";

		if ($this->check_current_query && !$this->check_ascii($query))
		{
			$strippedQuery = $this->strip_invalid_text_from_query($query);
			$this->flush();

			if ($strippedQuery !== $query)
			{
				$this->insert_id = 0;
				return false;
			}
		}

		$this->check_current_query = true;
		$this->last_query = $query;

		$this->doQuery($query);

		$errorCode = $this->connection->getPdo()->errorInfo()[1] ?? 0;

		if ($errorCode === 2006)
		{
			if ($this->check_connection())
			{
				$this->doQuery($query);
			}
			else
			{
				$this->insert_id = 0;
				return false;
			}
		}

		$this->last_error = $this->connection->getPdo()->errorInfo()[2] ?? null;

		if ($this->last_error)
		{
			if ($this->insert_id && preg_match('/^\s*(insert|replace)\s/i', $query))
				$this->insert_id = 0;

			$this->print_error();
			return false;
		}

		if (preg_match('/^\s*(create|alter|truncate|drop)\s/i', $query))
		{
			return $this->result;
		}
		else if (preg_match('/^\s*(insert|delete|update|replace)\s/i', $query))
		{
			$this->rows_affected = $this->result->rowCount();
			$this->insert_id = $this->connection->lastInsertIdInteger();

			return $this->rows_affected;
		}
		else
		{
			$numRows = 0;

			while ($row = $this->result->getPdoStatement()->fetch(PDO::FETCH_OBJ))
			{
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
			$this->connection->prepare(sprintf('SET NAMES \'%s\' COLLATE \'%s\'', $charset, $collate))->run();
		else
			$this->connection->prepare(sprintf('SET NAMES \'%s\'', $charset))->run();
	}

	/**
	 * {@inheritdoc}
	 * @author Bas Milius <bas@mili.us>
	 * @since 1.0.0
	 */
	public final function set_sql_mode($modes = [])
	{
		if (empty($modes))
		{
			$smt = $this->connection->prepare('SELECT @@SESSION.sql_mode');
			$smt->run();
			$res = $smt->getPdoStatement()->fetch(PDO::FETCH_NUM);

			$modes = array_filter(explode(',', $res[0]), fn($val) => !empty($val));
		}

		$modes = array_change_key_case($modes, CASE_UPPER);
		$incompatibleModes = (array)apply_filters('incompatible_sql_modes', $this->incompatible_modes);

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

		try
		{
			$this->result = $this->connection->prepare($query);
			$this->result->run();
		}
		catch (PDOException $err)
		{
		}

		$this->num_queries++;

		if (defined('SAVEQUERIES') && SAVEQUERIES)
			$this->queries[] = [$query, $this->timer_stop(), $this->get_caller()];
	}

}
