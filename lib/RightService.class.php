<?php

class_exists('DatabaseService') || require(NLB_LIB_ROOT.'DatabaseService.class.php');
class_exists('Right') || require(NLB_LIB_ROOT.'Right.class.php');

/**
 * The RightService is a service layer class that provides useful methods for dealing with Right objects
 */
class RightService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the RightService class
	 * @return RightService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the RightService class
	 * @return RightService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new RightService();
		}
		return self::$instance;
	}
	
	/**
	 * Returns a Right object based on the right_name provided
	 * @param string $right_name
	 * @return Right
	 */
	public function getRightByName($right_name)
	{
		$query = "SELECT * FROM `rights` WHERE `right_name` = ?";
		$res = $this->DB->getSelectArray($query, $right_name);
		if(is_array($res) && count($res) == 1)
		{
			$right = new Right($res[0]['rid']);
			return $right;
		}
	}
}
