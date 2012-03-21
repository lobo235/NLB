<?php

class Log
{
	public static function status($src, $message)
	{
		self::message('status', $src, $message);
	}

	public static function warning($src, $message)
	{
		self::message('warning', $src, $message);
	}

	public static function error($src, $message)
	{
		self::message('error', $src, $message);
	}

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
