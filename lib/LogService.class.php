<?php

/**
 * The LogService class is a service level class that handles the logging/emailing of status, warning, and error messages
 */
class LogService
{
	private static $instance;
	private $logfileresource = FALSE;

	/**
	 * The constructor for the LogService class
	 * @return LogService
	 */
	private function __construct()
	{
		
	}
	
	public function __destruct()
	{
		if($this->logfileresource !== FALSE)
		{
			fclose($this->logfileresource);
		}
	}

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the LogService class
	 * @return LogService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new LogService();
		}

		return self::$instance;
	}
	
	/**
	 * Logs a status message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public function status($src, $message)
	{
		$this->message('status', $src, $message);
	}

	/**
	 * Logs a warning message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public function warning($src, $message)
	{
		$this->message('warning', $src, $message);
	}

	/**
	 * Logs an error message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public function error($src, $message)
	{
		$this->message('error', $src, $message);
	}

	/**
	 * This private function is used by the other methods in this class to log/email messages
	 * @param string $type
	 * @param string $src
	 * @param string $message
	 * @return void
	 */
	private function message($type, $src, $message)
	{
		$datetime = date('Y-m-d h:i:sa');
		$server = $_SERVER['SERVER_NAME'];
		$uri = $_SERVER['REQUEST_URI'];
		if(NLB_LOG_DEST_EMAIL != '')
		{
			$subject = strtoupper($type).": $server";
			$msg = "Date/Time: $datetime\n";
			$msg .= "Request URI: $uri\n";
			$msg .= "Source: $src\n";
			$msg .= "Message: $message\n";
			// send the email
			mail(NLB_LOG_DEST_EMAIL, $subject, $msg);
		}
		if(NLB_LOG_DEST_FILE != '')
		{
			// append to file
			$error = "[$datetime] $server$uri $src\n$message\n";
			$this->openLogFile();
			fwrite($this->logfileresource, $error);
		}
	}
	
	private function openLogFile()
	{
		if($this->logfileresource == FALSE && NLB_LOG_DEST_FILE != '')
		{
			$this->logfileresource = fopen(NLB_LOG_DEST_FILE, 'a');
		}
	}
}
