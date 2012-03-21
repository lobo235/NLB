<?php

require_once(NLB_LIB_ROOT.'Log.class.php');
require_once(NLB_LIB_ROOT.'DBException.class.php');

/**
 * The DB class is a service layer class that provides an API for interacting with the database
 */
class DB
{
	private static $instance;
	private static $connection;

	/**
	 * The constructor for the DB class
	 * @return DB
	 */
	private function __construct()
	{
		try
		{
			$this->connection = new PDO('mysql:host='.NLB_MYSQL_HOST.';dbname='.NLB_MYSQL_DB, NLB_MYSQL_USER, NLB_MYSQL_PASS);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
			Log::error('DB __construct()', $e->getMessage());
			throw new DBException('Unable to connect to the Database', 1);
		}
	}

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the DB class
	 * @return DB 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new DB();
		}

		return self::$instance;
	}

	/**
	 * Returns an associative array built from the results of the given query run with the given params
	 * @param string $query The parameterized query to run
	 * @param array $params The parameters to use to execute the query
	 * @return array|false an associative array built from the results of the query or false if the query failed
	 */
	public function getSelectArray($query, $params = NULL)
	{
		$pstmt = $this->executePreparedQuery($query, $params);
		if($pstmt !== FALSE)
		{
			$res = $pstmt->fetchAll(PDO::FETCH_ASSOC);
			return (count($res) > 0 ? $res : FALSE);
		}
		return FALSE;
	}

	/**
	 * Returns the value in the first column of the first row of the results from the given query executed with the given params
	 * @param string $query The parameterized query to run
	 * @param array $params The parameters to use to execute the query
	 * @return mixed|false the value in the first column of the first row of the query result or false if the query failed
	 */
	public function getSelectFirst($query, $params = NULL)
	{
		$pstmt = $this->executePreparedQuery($query, $params);
		if($pstmt !== FALSE)
		{
			$res = $pstmt->fetch(PDO::FETCH_NUM);
			$pstmt->closeCursor(); // This line may be necessary when multiple rows are returned by the query
			return (isset($res[0]) ? $res[0] : FALSE);
		}
		return FALSE;
	}

	/**
	 * Executes an update/insert query using the given params and returns the last insert id or false on failure
	 * @param string $query The parameterized query to run
	 * @param array $params The parameters to use to execute the query
	 * @return int|false the last insert id resulting from this query being executed or false if the query failed 
	 */
	public function execUpdate($query, $params = NULL)
	{
		$pstmt = $this->executePreparedQuery($query, $params);
		if($pstmt !== FALSE)
		{
			return $this->connection->lastInsertId();
		}
		return FALSE;
	}

	/**
	 * Executes a query or other sql statement and returns true on success or false on failure
	 * @param string $query The parameterized query to run
	 * @param array $params The parameters to use to execute the query
	 * @return bool true if the query ran successfully, otherwise, false
	 */
	public function exec($query, $params = NULL)
	{
		return ($this->executePreparedQuery($query, $params) !== FALSE);
	}

	/**
	 * A private method that is used by the other methods in this class to prepare and execute a query
	 * @param string $query The parameterized query to run
	 * @param array $params The parameters to use to execute the query
	 * @return PDOStatement|false The executed PDOStatement or false on failure 
	 */
	private function executePreparedQuery($query, $params = NULL)
	{
		// If the parameter passed in was not an array (single value) wrap it in an array
		if($params !== NULL && !is_array($params))
		{
			$params = array($params);
		}

		try
		{
			if($this->connection !== NULL)
			{
				$pstmt = $this->connection->prepare($query);
				if($pstmt->execute($params))
				{
					return $pstmt;
				}
				else
				{
					throw new DBException('Could not execute query', DBException::QUERY_ERROR, $query, $params);
				}
			}
			else
			{
				Log::error('DB executePreparedQuery()', 'Cannot run query because the connection is NULL');
			}
		}
		catch(PDOException $e)
		{
			Log::error('DB executePreparedQuery()', $e->getMessage());
			throw new DBException('Could not prepare query', DBException::QUERY_ERROR, $query, $params);
		}
		return FALSE;
	}
}
