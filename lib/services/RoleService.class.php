<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('Role') || require_once(NLB_LIB_ROOT.'dom/Role.class.php');

/**
 * The RoleService is a service layer class that provides useful methods for dealing with Role objects
 */
class RoleService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the RoleService class
	 * @return RoleService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the RoleService class
	 * @return RoleService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new RoleService();
		}
		return self::$instance;
	}
	
	/**
	 * Returns a Role object based on the role_name provided
	 * @param string $role_name
	 * @return Role
	 */
	public function getRoleByName($role_name)
	{
		$query = "SELECT * FROM `roles` WHERE `role_name` = ?";
		$res = $this->DB->getSelectArray($query, $role_name);
		if(is_array($res) && count($res) == 1)
		{
			$role = new Role($res[0]['rid']);
			return $role;
		}
	}
}
