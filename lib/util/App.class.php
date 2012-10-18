<?php

class_exists('UrlAliasService') || require_once(NLB_LIB_ROOT.'services/UrlAliasService.class.php');

/**
 * The App class provides information about the Application itself such as settings, paths, etc.
 */
class App
{
	private static $instance;
	private $siteFolder;
	private $UrlAliasService;

	/**
	 * The constructor for the App class
	 * @return App
	 */
	private function __construct()
	{
		$this->siteFolder = $GLOBALS['siteDirectory'];
		$this->UrlAliasService = UrlAliasService::getInstance();
	}

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
	
	/**
	 * Returns the urlRoot of this App
	 * @return string
	 */
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
	
	/**
	 * Creates a link that uses the correct urlRoot and URL alias
	 */
	public function l($path)
	{
		$link = $this->urlRoot();
		$link .= $this->UrlAliasService->getAlias($path);
		return $link;
	}
	
	/**
	 * Returns the siteFolder of this App
	 * @return string 
	 */
	public function siteFolder()
	{
		if($this->siteFolder)
		{
			return $this->siteFolder;
		}
		else
		{
			return 'default';
		}
	}
}