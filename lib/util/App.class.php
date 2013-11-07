<?php

class_exists('UrlAliasService') || require_once(NLB_LIB_ROOT.'services/UrlAliasService.class.php');
class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('StringUtils') || require_once(NLB_LIB_ROOT.'util/StringUtils.class.php');

/**
 * The App class provides information about the Application itself such as settings, paths, etc.
 */
class App
{
	private static $instance;
	private $siteFolder;
	private $UrlAliasService;
	private $DB;
	private $su;
	private $objects;

	/**
	 * The constructor for the App class
	 * @return App
	 */
	private function __construct()
	{
		$this->siteFolder = $GLOBALS['siteDirectory'];
		$this->UrlAliasService = UrlAliasService::getInstance();
		$this->DB = DatabaseService::getInstance();
		$this->su = StringUtils::getInstance();
		$this->objects = array();
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
	
	/**
	 * Gets the current path. Accounts for different URL roots that may be configured
	 * @return the current path (not including the URL root configured in NLB_URL_ROOT
	 */
	public function getCurrentPath()
	{
		return $this->su->str_replace_once($this->urlRoot(), '', $_SERVER['REQUEST_URI']);
	}
	
	/**
	 * Gets the registered objects (classes) for this App
	 * @return array the list of objects (classes) that have been registered for this App
	 */
	public function getObjects()
	{
		return $this->objects;
	}
	
	/**
	 * Registers an object (class) for this App
	 * @param string object (class) name to register
	 */
	public function registerObject($name)
	{
		array_push($this->objects, $name);
	}
	
	/**
	 * This function will include a class smartly (first tries to load the class from the site
	 * specific folder and then tries to load a standard NLB lib)
	 * @param string the type of the class to load (e.g. dom or services)
	 * @param string the name of the class to load (e.g. User or Node) 
	 */
	public function loadClass($type, $name)
	{
		// First look for the class in the site specific lib folder
		if(file_exists(NLB_SITE_ROOT.'sites/'.$this->siteFolder().'/lib/'.$type.'/'.$name.'.class.php'))
		{
			class_exists($name) || require_once(NLB_SITE_ROOT.'sites/'.$this->siteFolder().'/lib/'.$type.'/'.$name.'.class.php');
		}
		// Then look for the handler in the default NLB handlers
		else
		{
			class_exists($name) || require_once(NLB_SITE_ROOT.'lib/'.$type.'/'.$name.'.class.php');
		}
	}
}