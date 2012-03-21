<?php

require_once(NLB_LIB_ROOT.'Log.class.php');
require_once(NLB_LIB_ROOT.'DBException.class.php');

class DB
{
	private static $instance;
	private static $connection;

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

	// This declaration of a private __clone method helps enforce the singleton pattern
	private function __clone() { }

	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new DB();
		}

		return self::$instance;
	}

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

	public function execUpdate($query, $params = NULL)
	{
		$pstmt = $this->executePreparedQuery($query, $params);
		if($pstmt !== FALSE)
		{
			return $this->connection->lastInsertId();
		}
		return FALSE;
	}

	public function exec($query, $params = NULL)
	{
		return ($this->executePreparedQuery($query, $params) !== FALSE);
	}

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
