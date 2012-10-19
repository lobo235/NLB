<?php

class_exists('UrlAliasService') || require_once(NLB_LIB_ROOT.'services/UrlAliasService.class.php');
class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');

/**
 * The App class provides information about the Application itself such as settings, paths, etc.
 */
class App
{
	private static $instance;
	private $siteFolder;
	private $UrlAliasService;
	private $DB;

	/**
	 * The constructor for the App class
	 * @return App
	 */
	private function __construct()
	{
		$this->siteFolder = $GLOBALS['siteDirectory'];
		$this->UrlAliasService = UrlAliasService::getInstance();
		$this->DB = DatabaseService::getInstance();
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
	
	/**
	 * Gets a variable from the vars table returning the value in $default if the variable doesn't exist. The value in the vars table is
	 * stored in a serialized format. This function automatically unserializes the value before it is returned.
	 * @return mixed the unserialized value assigned to this variable or NULL if no variable exists and no $default value passed in
	 * @param $name the name of the variable
	 * @param $default the default value to pass back if the variable doesn't exist
	 */
	public function getVar($name, $default = NULL)
	{
		$query = "SELECT `value` FROM `vars` WHERE `name` = ?";
		try
		{
			$res = $this->DB->getSelectFirst($query, $name);
		}
		catch(Exception $e)
		{
			$res = FALSE;
		}
		
		if($res !== FALSE)
		{
			return unserialize($res);
		}
		else
		{
			return $default;
		}
	}
	
	/**
	 * Stores a variable in the vars table in a serialized format
	 * @param $name the name of the variable to set
	 * @param $value the value of the variable
	 */
	public function setVar($name, $value = NULL)
	{
		$query = "REPLACE INTO `vars` (`name`, `value`) VALUES (?, ?)";
		$params = array($name, serialize($value));
		$this->DB->execUpdate($query, $params);
	}
	
	/**
	 * Deletes a variable from the vars table
	 * @param $name the name of the variable to remove
	 */
	public function unsetVar($name)
	{
		$query = "DELETE FROM `vars` WHERE `name` = ?";
		$this->DB->exec($query, $name);
	}
}