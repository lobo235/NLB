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
			foreach($res as $row)
			{
				$userRole = new UserRole($row['urid']);
				$ret[] = $userRole;
			}
		}
		return $ret;
	}
	
	/**
	 * Returns an array of the user roles in the roles table
	 * @return array an associative array of the user roles in the roles table. The key of each entry is the rid from the database, the value is the role_name
	 */
	public function getAvailableUserRoles()
	{
		static $ret = NULL;
		if($ret === NULL)
		{
			$query = "SELECT `rid`, `role_name` FROM `roles` ORDER BY `rid`";
			$res = $this->DB->getSelectArray($query);
			if(is_array($res) && count($res) != 0)
			{
				foreach($res as $row)
				{
					$ret[$row['rid']] = $row['role_name'];
				}
			}
		}
		else
		{
			return $ret;
		}
	}
	
	/**
	 * This function removes a user role from a user
	 * @param $userRole the user role to remove from the user
	 */
	public function removeRoleFromUser(UserRole $userRole)
	{
		$query = "DELETE FROM `user_roles` WHERE `urid` = ?";
		$res = $this->DB->exec($query, $userRole->getUrid());
	}
	
	/**
	 * This function adds a user role to a user
	 * @param $uid the uid of the user to add the role to
	 * @param $rid the rid of the role to add to the user
	 * @return int|false the urid of the user_role inserted into the db or FALSE if the role couldn't be saved
	 */
	public function addRoleToUser($uid, $rid)
	{
		$query = "REPLACE INTO `user_roles` (`uid`, `rid`) VALUES(?, ?)";
		$res = $this->DB->execUpdate($query, array($uid, $rid));
		return $res;
	}
}
