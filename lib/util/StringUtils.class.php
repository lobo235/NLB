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
}
