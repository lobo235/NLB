<?php

/**
 * The StringUtils has a bunch of useful methods for dealing with Strings
 */
class StringUtils {
	private static $instance;

	/**
	 * The constructor for the StringUtils class
	 * @return DatabaseService
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
	
	public function prettyFromUrl($str)
	{
		return ucwords(trim(preg_replace('/\s+/', ' ', preg_replace('/[\-_]/', ' ', $str))));
	}
	
	public function prettyToUrl($str)
	{
		return preg_replace('/[_]|\s+/', '-', preg_replace('/[^A-Za-z0-9_\s\s+]/', '', trim($str)));
	}
}
