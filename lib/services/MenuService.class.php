<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');

/**
 * The MenuService is a service layer class that provides useful methods for dealing with Menu objects
 */
class MenuService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the MenuService class
	 * @return MenuService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the MenuService class
	 * @return MenuService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new MenuService();
		}
		return self::$instance;
	}
	
	/**
	 * Returns an array of the menus in the menus table
	 * @return array an associative array of the menus in the menus table. The key of each entry is the mid from the database, the value is the menu_name
	 */
	public function getAvailableMenus()
	{
		static $ret = NULL;
		if($ret === NULL)
		{
			$ret = array();
			$query = "SELECT `mid`, `menu_name` FROM `menus` ORDER BY `mid`";
			$res = $this->DB->getSelectArray($query);
			if(is_array($res) && count($res) != 0)
			{
				foreach($res as $row)
				{
					$ret[$row['mid']] = $row['menu_name'];
				}
			}
			return $ret;
		}
		else
		{
			return $ret;
		}
	}
}
