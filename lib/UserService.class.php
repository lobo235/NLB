<?php

class_exists('DatabaseService') || require(NLB_LIB_ROOT.'DatabaseService.class.php');
class_exists('User') || require(NLB_LIB_ROOT.'User.class.php');
class_exists('UserRight') || require(NLB_LIB_ROOT.'UserRight.class.php');

/**
 * The UserService is a service layer class that provides useful methods for dealing with User objects
 */
class UserService {
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the UserService class
	 * @return UserService
	 */
	private function __construct() {
		$this->DB = DatabaseService::getInstance();
	}
	
	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }
	
	/**
	 * Returns an instance of the UserService class
	 * @return UserService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new UserService();
		}
		return self::$instance;
	}
	
	public function getUser()
	{
		// DEBUG
		//return new User(2);
		
		$anonymousUser = FALSE;
		
		// non-invasive session checking. Will not set a session cookie for users who don't already have one.
		$session_cookie_name = session_name( );
		if(isset($_COOKIE[$session_cookie_name]) && $_COOKIE[$session_cookie_name] != '')
		{
			session_start( );
			if(isset($_SESSION['nlb_user_uid']) && is_numeric($_SESSION['nlb_user_uid']))
			{
				return new User($_SESSION['nlb_user_uid']);
			}
			else
			{
				$anonymousUser = TRUE;
			}
		}
		else {
			$anonymousUser = TRUE;
		}
		
		if($anonymousUser === TRUE)
		{
			$user = new User();
			$user->setUid(0); // Anonymous user uid is 0
			$user->setFirstName('Anonymous');
			$user->setLastName('User');
			$user->setUsername('anonymous');
			$userRight = new UserRight();
			$userRight->setRight('anonymous user');
			$user->setUserRights(array($userRight));
			return $user;
		}
	}
	
	public function userHasRight(User $user, $right)
	{
		if($right == 'anonymous user')
		{
			return TRUE;
		}
		
		if(!$user->userRightsLoaded())
		{
			$this->loadUserRights($user);
		}
		$hasRight = FALSE;
		foreach($user->getUserRights() as $rightObj)
		{
			if($rightObj->getRight() == $right)
			{
				$hasRight = TRUE;
				break;
			}
		}
		return $hasRight;
	}
	
	private function loadUserRights(User $user)
	{
		$query = "SELECT * FROM `user_rights` WHERE `uid` = ?";
		$res = $this->DB->getSelectArray($query, $user->getUid());
		$rights = array();
		if(is_array($res) && count($res) > 0)
		{
			foreach($res as $row)
			{
				$right = new UserRight();
				$right->setRid($row['rid']);
				$right->setUid($row['uid']);
				$right->setRight($row['right']);
				
				$rights[] = $right;
			}
		}
		$user->setUserRights($rights);
	}
	
	public function loginUser($username, $password)
	{
		$query = "SELECT `uid` FROM `users` WHERE (`username` = ? OR `email` = ?) AND `password` = ?";
		$params = array($username, $username, md5(PASSWORD_HASH_SALT.$password));
		$uid = $this->DB->getSelectFirst($query, $params);
		if($uid !== FALSE)
		{
			session_start();
			$_SESSION['nlb_user_uid'] = $uid;
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
