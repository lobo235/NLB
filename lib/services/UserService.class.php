<?php

class_exists('DatabaseService') || require(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('User') || require(NLB_LIB_ROOT.'dom/User.class.php');
class_exists('Right') || require(NLB_LIB_ROOT.'dom/Right.class.php');
class_exists('UserRight') || require(NLB_LIB_ROOT.'dom/UserRight.class.php');

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
	
	/**
	 * Returns a new/empty User object
	 * @return User
	 */
	public function newUser()
	{
		return new User();
	}
	
	public function getUser()
	{
		$anonymousUser = FALSE;
		
		// non-invasive session checking. Will not set a session cookie for users who don't already have one.
		$session_cookie_name = session_name();
		if(isset($_COOKIE[$session_cookie_name]) && $_COOKIE[$session_cookie_name] != '')
		{
			session_set_cookie_params(NLB_SESSION_LENGTH);
			session_start();
			if(isset($_SESSION['nlb_user_uid']) && is_numeric($_SESSION['nlb_user_uid']))
			{
				return new User($_SESSION['nlb_user_uid']);
			}
			else
			{
				$anonymousUser = TRUE;
			}
		}
		else
		{
			$anonymousUser = TRUE;
		}
		
		if($anonymousUser === TRUE)
		{
			$user = new User();
			$user->setUid(0); // Anonymous user uid is 0
			$user->setFirstName('Anonymous');
			$user->setLastName('User');
			$user->setUsername('anonymous');
			$userRight = new UserRight(1);
			$user->setUserRights(array($userRight));
			return $user;
		}
	}
	
	/**
	 * Checks to see if the given user has the given right
	 * @param User $user
	 * @param type $right_name
	 * @return boolean TRUE if the user has the right, otherwise, FALSE
	 */
	public function userHasRight(User $user, $right_name)
	{
		if($right_name == 'anonymous user')
		{
			return TRUE;
		}
		elseif($right_name == 'authenticated user' && $user->getUid() != 0)
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
			$right = new Right($rightObj->getRid());
			if($right_name == $right->getRightName())
			{
				$hasRight = TRUE;
				break;
			}
		}
		return $hasRight;
	}
	
	/**
	 * Loads the UserRights for the given user
	 * @param User $user the user to load rights for
	 * @return void
	 */
	private function loadUserRights(User $user)
	{
		$rights = array();
		$query = "SELECT * FROM `user_rights` WHERE `uid` = ?";
		$res = $this->DB->getSelectArray($query, $user->getUid());
		if(is_array($res) && count($res) > 0)
		{
			foreach($res as $row)
			{
				$right = new UserRight();
				$right->setUrid($row['urid']);
				$right->setUid($row['uid']);
				$right->setRid($row['rid']);
				
				$rights[] = $right;
			}
		}
		
		$user->setUserRights($rights);
	}
	
	/**
	 * Given a username and password, this method will try to log a user into the system by starting their session after validating that the
	 * user exists in the DB and the password is correct
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function loginUser($username, $password)
	{
		$query = "SELECT `uid` FROM `users` WHERE (`username` = ? OR `email` = ?) AND `password` = ?";
		$params = array($username, $username, md5(NLB_PASSWORD_HASH_SALT.$password));
		$uid = $this->DB->getSelectFirst($query, $params);
		if($uid !== FALSE)
		{
			session_set_cookie_params(NLB_SESSION_LENGTH);
			session_start();
			$_SESSION['nlb_user_uid'] = $uid;
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/**
	 * Destroys the session for the current user which effectively logs them out of the system
	 * @return void
	 */
	public function logoutUser()
	{
		unset($_SESSION['nlb_user_uid']);
		session_destroy();
	}
	
	/**
	 * Hashes the password for the given User. This is generally called right before saving a new User object
	 * @param User $user
	 * @return void
	 */
	public function hashUserPassword(User $user)
	{
		$user->setPassword(md5(NLB_PASSWORD_HASH_SALT.$user->getPassword()));
	}
	
	/**
	 * Gets a list of the Users for this site
	 * @return User[]
	 */
	public function getUsers()
	{
		$users = array();
		$query = "SELECT `uid` FROM `users`";
		$res = $this->DB->getSelectArray($query);
		if(is_array($res) && count($res) > 0)
		{
			foreach($res as $row)
			{
				$users[] = new User($row['uid']);
			}
		}
		return $users;
	}
}
