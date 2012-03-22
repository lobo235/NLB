<?php

/**
 * The LogService class is a service level class that handles the logging/emailing of status, warning, and error messages
 */
class LogService
{
	/**
	 * Logs a status message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public static function status($src, $message)
	{
		self::message('status', $src, $message);
	}

	/**
	 * Logs a warning message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public static function warning($src, $message)
	{
		self::message('warning', $src, $message);
	}

	/**
	 * Logs an error message
	 * @param string $src The source of this message
	 * @param string $message The message to log
	 * @return void
	 */
	public static function error($src, $message)
	{
		self::message('error', $src, $message);
	}

	/**
	 * This private function is used by the other methods in this class to log/email messages
	 * @param string $type
	 * @param string $src
	 * @param string $message
	 * @return void
	 */
	private static function message($type, $src, $message)
	{
		if(NLB_LOG_DEST_EMAIL != '')
		{
			$subject = strtoupper($type).': '.$_SERVER['SERVER_NAME'];
			$msg = "Date/Time: ".date('Y-m-d h:i:s a')."\n";
			$msg .= "Request URI: ".$_SERVER['REQUEST_URI']."\n";
			$msg .= "Source: $src\n";
			$msg .= "Message: $message\n";
			// send the email
			mail(NLB_LOG_DEST_EMAIL, $subject, $msg);
		}
		elseif(NLB_LOG_DEST_FILE != '')
		{
			// append to file
		}
	}
}
