<?php

/*
 */

/**
 * The App class provides information about the Application itself such as settings, paths, etc.
 */
class App
{
	private static $instance;

	/**
	 * The constructor for the App class
	 * @return App
	 */
	private function __construct() { }

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the App class
	 * @return App
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new App();
		}

		return self::$instance;
	}
	
	public function urlRoot()
	{
		if(NLB_URL_ROOT != '')
		{
			return NLB_URL_ROOT;
		}
		else
		{
			return '/';
		}
	}
}