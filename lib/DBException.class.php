<?php

class DBException extends Exception
{
	const QUERY_ERROR = 2;

	protected $message = 'Unknown Database Exception';
	protected $code = 0;
	protected $query = NULL;
	protected $params = NULL;

	public function __construct($message = NULL, $code = 0, $query = NULL, $params = NULL)
	{
		if(!$message)
		{
			throw new DBException($this->message);
		}
		parent::__construct($message, $code);
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getParams()
	{
		return $this->params;
	}
}
