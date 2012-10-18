<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('UserRight') || require_once(NLB_LIB_ROOT.'dom/UserRight.class.php');

/**
 * The UserRightService is a service layer class that provides useful methods for dealing with UserRight objects
 */
class UserRightService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the UserRightService class
	 * @return UserRightService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the UserRightService class
	 * @return UserRightService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UserRightService();
		}
		return self::$instance;
	}
	
	/**
	 * Returns an array of UserRight objects for the uid provided
	 * @param int $uid the uid of a user
	 * @return UserRight[] an array of the UserRights for this user
	 */
	public function getUserRightsForUid($uid)
	{
		$ret = array();
		$query = "SELECT `urid` FROM `user_rights` WHERE `uid` = ? ORDER BY `urid`";
		$res = $this->DB->getSelectArray($query, $uid);
		if(is_array($res) && count($res) != 0)
		{
			$userRight = new UserRight($res[0]['urid']);
			$ret[] = $userRight;
		}
		return $ret;
	}
}
