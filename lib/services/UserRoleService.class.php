<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('UserRole') || require_once(NLB_LIB_ROOT.'dom/UserRole.class.php');
class_exists('MenuItemRole') || require_once(NLB_LIB_ROOT.'dom/MenuItemRole.class.php');

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
	 * Returns an array of the user roles in the roles table
	 * @return array an associative array of the user roles in the roles table. The key of each entry is the rid from the database, the value is the role_name
	 */
	public function getAvailableUserRoles()
	{
		static $ret = NULL;
		if($ret === NULL)
		{
			$ret = array();
			$query = "SELECT `rid`, `role_name` FROM `roles` ORDER BY `rid`";
			$res = $this->DB->getSelectArray($query);
			if(is_array($res) && count($res) != 0)
			{
				foreach($res as $row)
				{
					$ret[$row['rid']] = $row['role_name'];
				}
			}
			return $ret;
		}
		else
		{
			return $ret;
		}
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
	 * This function removes a user role from a user
	 * @param $userRole the user role to remove from the user
	 * @return bool true if the user role was removed successfully, otherwise, false
	 */
	public function removeRoleFromUser(UserRole $userRole)
	{
		$query = "DELETE FROM `user_roles` WHERE `urid` = ?";
		$res = $this->DB->exec($query, $userRole->getUrid());
		return $res;
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
	
	/**
	 * Returns an array of MenuItemRoles for the miid provided
	 * @param $miid the miid of the MenuItem to find roles for
	 * @return MenuItemRole[] an array of the roles that can access this miid
	 */
	public function getRolesForMiid($miid)
	{
		$ret = array();
		$query = "SELECT `mirid` FROM `menu_item_roles` WHERE `miid` = ? ORDER BY `mirid`";
		$res = $this->DB->getSelectArray($query, $miid);
		if(is_array($res) && count($res) != 0)
		{
			foreach($res as $row)
			{
				$menuItemRole = new MenuItemRole($row['mirid']);
				$ret[] = $menuItemRole;
			}
		}
		return $ret;
	}
	
	/**
	 * This function adds a MenuItemRole to a menu_item
	 * @param $miid the miid of the menu_item to add the role to
	 * @param $rid the rid of the role to add to the menu_item
	 * @return int|false the mirid of the MenuItemRole inserted into the db or FALSE if the role couldn't be saved
	 */
	public function addRoleToMenuItem($miid, $rid)
	{
		$query = "REPLACE INTO `menu_item_roles` (`miid`, `rid`) VALUES(?, ?)";
		$res = $this->DB->execUpdate($query, array($miid, $rid));
		return $res;
	}
}
