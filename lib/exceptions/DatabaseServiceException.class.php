<?php

/**
 * The DatabaseServiceException class extends the Exception class and is used by the DB class to throw errors
 */
class DatabaseServiceException extends Exception
{
	const SERVER_ERROR = 1;
	const QUERY_ERROR = 2;
	const USER_ERROR = 3;
	const DB_ERROR = 4;
	const QUERY_ERROR_UNIQUE = 5;
	const UNSPECIFIED_ERROR = 99;

	protected $message = 'Unknown Database Exception';
	protected $code = 0;
	protected $query = NULL;
	protected $params = NULL;

	/**
	 * The constructor for the DatabaseServiceException class
	 * @param string $message the exception message
	 * @param int $code the exception code
	 * @param string $query the query related to this exception if applicable
	 * @param array $params the query params related to this exception if applicable
	 */
	public function __construct($message = NULL, $code = 0, $query = NULL, $params = NULL)
	{
		if(!$message)
		{
			throw new DatabaseServiceException($this->message);
		}
		parent::__construct($message, $code);
	}

	/**
	 * Returns the query associated with this exception
	 * @return string the query associated with this exception
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Returns the query params associated with this exception
	 * @return array the query params associated with this exception
	 */
	public function getParams()
	{
		return $this->params;
	}
}
