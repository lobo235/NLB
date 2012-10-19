<?php

class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('User') || require_once(NLB_LIB_ROOT.'dom/User.class.php');
class_exists('Role') || require_once(NLB_LIB_ROOT.'dom/Role.class.php');
class_exists('UserRole') || require_once(NLB_LIB_ROOT.'dom/UserRole.class.php');

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
			$user = new User(1);
			return $user;
		}
	}
	
	/**
	 * Checks to see if the given user has the given role
	 * @param User $user
	 * @param type $role_name
	 * @return boolean TRUE if the user has the role, otherwise, FALSE
	 */
	public function userHasRole(User $user, $role_name)
	{
		if($role_name == 'anonymous user')
		{
			return TRUE;
		}
		elseif($role_name == 'authenticated user' && $user->getUid() > 1)
		{
			return TRUE;
		}
		
		if(!$user->userRolesLoaded())
		{
			$this->loadUserRoles($user);
		}
		$hasRole = FALSE;
		foreach($user->getUserRoles() as $roleObj)
		{
			$role = new Role($roleObj->getRid());
			if($role_name == $role->getRoleName())
			{
				$hasRole = TRUE;
				break;
			}
		}
		return $hasRole;
	}
	
	/**
	 * Loads the UserRoles for the given user
	 * @param User $user the user to load roles for
	 * @return void
	 */
	private function loadUserRoles(User $user)
	{
		$roles = array();
		$query = "SELECT * FROM `user_roles` WHERE `uid` = ?";
		$res = $this->DB->getSelectArray($query, $user->getUid());
		if(is_array($res) && count($res) > 0)
		{
			foreach($res as $row)
			{
				$role = new UserRole();
				$role->setUrid($row['urid']);
				$role->setUid($row['uid']);
				$role->setRid($row['rid']);
				
				$roles[] = $role;
			}
		}
		
		$user->setUserRoles($roles);
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
		$query = "SELECT u.`uid` FROM `users` u, `entities` e WHERE u.`eid` = e.`eid` AND e.`status` = 1 AND (u.`username` = ? OR u.`email` = ?) AND u.`password` = ?";
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
		if(isset($_SESSION))
		{
			unset($_SESSION['nlb_user_uid']);
		}
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
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
