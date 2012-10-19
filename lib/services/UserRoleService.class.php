<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('UserRole') || require_once(NLB_LIB_ROOT.'dom/UserRole.class.php');

/**
 * The UserRoleService is a service layer class that provides useful methods for dealing with UserRole objects
 */
class UserRoleService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the UserRoleService class
	 * @return UserRoleService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the UserRoleService class
	 * @return UserRoleService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UserRoleService();
		}
		return self::$instance;
	}
	
	/**
	 * Returns an array of UserRole objects for the uid provided
	 * @param int $uid the uid of a user
	 * @return UserRole[] an array of the UserRoles for this user
	 */
	public function getUserRolesForUid($uid)
	{
		$ret = array();
		$query = "SELECT `urid` FROM `user_roles` WHERE `uid` = ? ORDER BY `urid`";
		$res = $this->DB->getSelectArray($query, $uid);
		if(is_array($res) && count($res) != 0)
		{
			$userRole = new UserRole($res[0]['urid']);
			$ret[] = $userRole;
		}
		return $ret;
	}
}
