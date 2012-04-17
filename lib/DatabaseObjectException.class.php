<?php

/**
 * The DatabaseObjectException class extends the Exception class and is used by the DB class to throw errors
 */
class DatabaseObjectException extends Exception
{
	const UNIQUE_ERROR = 1;
	const UNSPECIFIED_ERROR = 99;

	protected $message = 'Unknown Database Exception';
	protected $code = 0;
	protected $query = NULL;
	protected $params = NULL;

	/**
	 * The constructor for the DatabaseObjectException class
	 * @param string $message the exception message
	 * @param int $code the exception code
	 * @param string $query the query related to this exception if applicable
	 * @param array $params the query params related to this exception if applicable
	 */
	public function __construct($message = NULL, $code = 0, $query = NULL, $params = NULL)
	{
		if(!$message)
		{
			throw new DatabaseObjectException($this->message);
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
