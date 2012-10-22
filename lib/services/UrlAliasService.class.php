<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');

/**
 * The UrlAliasService class provides useful methods for storing, retrieving, and using URL Aliases
 */
class UrlAliasService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the UrlAliasService class
	 * @return UrlAliasService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the UrlAliasService class
	 * @return UrlAliasService
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UrlAliasService();
		}

		return self::$instance;
	}
	
	/**
	 * Returns an alias for the given path if one exists, otherwise, just returns the path
	 * @param path the path to find an alias for
	 * @return string the alias that matched this path
	 */
	public function getAlias($path)
	{
		// Local caching to help with performance
		static $local_cache = NULL;
		if($local_cache === NULL)
		{
			$local_cache = array();
		}
		if(isset($local_cache[$path]))
		{
			return $local_cache[$path];
		}
		else
		{
			$query = "SELECT `alias` FROM `url_aliases` WHERE `path` = ?";
			$res = $this->DB->getSelectFirst($query, $path);
			$retval = NULL;
			if($res !== FALSE)
			{
				$retval = $res;
			}
			else
			{
				$retval = $path;
			}
			$local_cache[$path] = $retval;
			return $retval;
		}
	}
	
	/**
	 * Returns a path for the given alias if one exists, otherwise, just returns the alias
	 * @param alias the alias to find a path for
	 * @return string the path that matches this alias
	 */
	public function getPath($alias)
	{
		// Local caching to help with performance
		static $local_cache = NULL;
		if($local_cache === NULL)
		{
			$local_cache = array();
		}
		if(isset($local_cache[$alias]))
		{
			return $local_cache[$alias];
		}
		else
		{
			$query = "SELECT `path` FROM `url_aliases` WHERE `alias` = ?";
			$res = $this->DB->getSelectFirst($query, $alias);
			$retval = NULL;
			if($res !== FALSE)
			{
				$retval = $res;
			}
			else
			{
				$retval = $alias;
			}
			$local_cache[$alias] = $retval;
			return $retval;
		}
	}
	
	/**
	 * This function deletes an alias based on the path
	 * @param $path the path to delete the alias for
	 * @return bool TRUE if alias was deleted, otherwise, FALSE
	 */
	function deleteAliasForPath($path)
	{
		if($path != '')
		{
			$query = "DELETE FROM `url_aliases` WHERE `path` = ?";
			return $this->DB->exec($query, $path);
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * This function sets the alias associated with a particular path. If the path has already been aliased, this function updates the alias associated with the path
	 * @param $path the path to set an alias for
	 * @param $alias the alias to set
	 * @return bool TRUE if the alias was set successfully, otherwise, FALSE
	 */
	function setAlias($path, $alias)
	{
		if($path != $alias && $path != '' && $alias != '')
		{
			$query = "REPLACE INTO `url_aliases` (`path`,`alias`) VALUES (?,?)";
			return $this->DB->exec($query, array($path, $alias));
		}
		else
		{
			return FALSE;
		}
	}
}
