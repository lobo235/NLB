<?php

/**
 * The StringUtils has a bunch of useful methods for dealing with Strings
 */
class StringUtils {
	private static $instance;

	/**
	 * The constructor for the StringUtils class
	 * @return StringUtils
	 */
	private function __construct() { }

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the StringUtils class
	 * @return StringUtils
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new StringUtils();
		}

		return self::$instance;
	}
	
	/**
	 * This function takes text from a path component of a URL and makes it pretty
	 * @param string $str
	 * @return string
	 */
	public function prettyFromUrl($str)
	{
		return ucwords(trim(preg_replace('/\s+/', ' ', preg_replace('/[\-_]/', ' ', $str))));
	}
	
	/**
	 * This function takes pretty text and prepares it for use as a path component in a URL
	 * @param string $str
	 * @return string
	 */
	public function prettyToUrl($str)
	{
		return preg_replace('/[_]|\s+/', '-', preg_replace('/[^A-Za-z0-9_\s\s+]/', '', trim($str)));
	}
	
	/**
	 * This function works the same as PHP's str_replace but only replaces the first occurrence of a string
	 * @param string $search the text to search for
	 * @param string $replace the text to use as a replacement
	 * @param string $subject the string to perform the replacement on
	 * @return string the new string with the replacement completed
	 */
	function str_replace_once($search, $replace, $subject){ 

		if(strpos($subject, $search) !== false)
		{
			$occurrence = strpos($subject, $search);
			if($occurrence !== FALSE)
				return substr_replace($subject, $replace, $occurrence, strlen($search));
			else
				return $subject;
		}

		return $subject;
	}
}
