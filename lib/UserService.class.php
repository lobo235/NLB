<?php

require_once(NLB_LIB_ROOT.'User.class.php');
require_once(NLB_LIB_ROOT.'UserRights.class.php');

/**
 * The UserService is a service layer class that provides useful methods for dealing with User objects
 */
class UserService {
	private static $instance;
	
	/**
	 * The constructor for the UserService class
	 * @return UserService
	 */
	private function __construct() { }
	
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
		$anonymousUser = FALSE;
		
		// non-invasive session checking. Will not set a session cookie for users who don't already have one.
		$session_cookie_name = session_name( );
		if(isset($_COOKIE[$session_cookie_name]) && $_COOKIE[$session_cookie_name] != '')
		{
			session_start( );
			if(isset($_SESSION['nlb_user']) && $_SESSION['nlb_user']->getUid() !== FALSE)
			{
				return $_SESSION['nlb_user'];
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
			$userRights = new UserRights();
			$userRights->setRights(array('anonymous user'));
			$user->setUserRights($userRights);
			return $user;
		}
	}
	
	public function userHasRight(User $user, $right)
	{
		$hasRight = FALSE;
		foreach($user->getUserRights()->getRights() as $val)
		{
			if($right == $val)
			{
				$hasRight = TRUE;
				break;
			}
		}
		return $hasRight;
	}
}
