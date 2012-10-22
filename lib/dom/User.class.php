<?php

class_exists('DatabaseTable') || require_once(NLB_LIB_ROOT.'dao/DatabaseTable.class.php');
class_exists('DatabaseColumn') || require_once(NLB_LIB_ROOT.'dao/DatabaseColumn.class.php');
class_exists('Entity') || require_once(NLB_LIB_ROOT.'dom/Entity.class.php');
class_exists('UserRoleService') || require_once(NLB_LIB_ROOT.'services/UserRoleService.class.php');
class_exists('UserService') || require_once(NLB_LIB_ROOT.'services/UserService.class.php');

/**
 * The User class represents a user of the system
 */
class User extends Entity {
	protected $userRoles;
	protected $userRolesLoaded;
	protected $passwordEncrypted;
	
	/**
	 * The constructor for the User class
	 */
	public function __construct($uid = NULL)
	{
		parent::__construct();
		
		// Change the labels for Published/Unpublished
		$statusColumn = $this->tables[0]->getColumn('status');
		$statusColumn->setExtras(array(
				'radio' => array(
					1 => 'Active',
					0 => 'Blocked',
				)
			)
		);
		$this->tables[0]->addColumn($statusColumn);
		
		$this->primaryIdColumn = 'uid';
		
		$table = new DatabaseTable('users', 'uid');
		$table->addColumn(new DatabaseColumn('uid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('username', 'string', 32));
		$table->addColumn(new DatabaseColumn('password', 'string,password', 32));
		$table->addColumn(new DatabaseColumn('first_name', 'string', 32));
		$table->addColumn(new DatabaseColumn('last_name', 'string', 32));
		$table->addColumn(new DatabaseColumn('last_login_date', 'hidden,datetime'));
		$table->addColumn(new DatabaseColumn('email', 'string', 64, array(
			'size' => 50,
		)));
		$this->addTable($table);
		
		$this->specialFields[] = new DatabaseColumn('roles', 'select,multiple', NULL, array(
			'size' => 7,
			'options' => UserRoleService::getInstance()->getAvailableUserRoles(),
		));
		
		$this->setType('User');
		
		if($uid !== NULL)
		{
			$this->setField('uid', $uid);
			$this->lookup();
			$this->passwordEncrypted = TRUE;
		}
		else
		{
			$this->setLastLoginDate(NULL);
			$this->passwordEncrypted = FALSE;
		}
		
		$this->userRolesLoaded = FALSE;
		
		$this->setIdentifierField('username');
	}
	
	/**
	 * Sets the username for this User
	 * @param string $username the username for this User
	 */
	public function setUsername($username)
	{
		$this->setField('username', $username);
	}
	
	/**
	 * Sets the password for this User
	 * @param string $password the password for this User
	 */
	public function setPassword($password)
	{
		$this->setField('password', $password);
		$this->passwordEncrypted = FALSE;
	}
	
	/**
	 * Sets the first name for this User
	 * @param string $firstName the first name for this User
	 */
	public function setFirstName($firstName)
	{
		$this->setField('first_name', $firstName);
	}
	
	/**
	 * Sets the last name for this User
	 * @param string $lastName the last name for this User
	 */
	public function setLastName($lastName)
	{
		$this->setField('last_name', $lastName);
	}
	
	/**
	 * Sets the last login date for this User
	 * @param DateTime $lastLoginDate the last login date for this User
	 */
	public function setLastLoginDate($lastLoginDate)
	{
		$this->setField('last_login_date', $lastLoginDate);
	}
	
	/**
	 * Sets the email address for this User
	 * @param string $email the email address for this User
	 */
	public function setEmail($email)
	{
		$this->setField('email', $email);
	}
	
	/**
	 * Sets the user roles for this User
	 * @param UserRole[] $userRoles an array of UserRole objects for this User
	 */
	public function setUserRoles(array $userRoles)
	{
		$this->userRoles = $userRoles;
		$this->userRolesLoaded = TRUE;
	}
	
	/**
	 * Returns the username for this User
	 * @return string the username for this User
	 */
	public function getUsername()
	{
		return $this->getField('username');
	}
	
	/**
	 * Returns the password for this User
	 * @return string the password for this User
	 */
	public function getPassword()
	{
		return $this->getField('password');
	}
	
	/**
	 * Returns the first name for this User
	 * @return string the first name for this User
	 */
	public function getFirstName()
	{
		return $this->getField('first_name');
	}
	
	/**
	 * Returns the last name for this User
	 * @return string the last name for this User
	 */
	public function getLastName()
	{
		return $this->getField('last_name');
	}
	
	/**
	 * Returns the last login date for this User
	 * @return DateTime the last login date for this User
	 */
	public function getLastLoginDate()
	{
		return $this->getField('last_login_date');
	}
	
	/**
	 * Returns the email address for this User
	 * @return string the email address for this User
	 */
	public function getEmail()
	{
		return $this->getField('email');
	}
	
	/**
	 * Returns the user roles for this User
	 * @return UserRole[] the array of UserRole objects for this User
	 */
	public function getUserRoles()
	{
		return $this->userRoles;
	}
	
	/**
	 * Returns TRUE if the user roles have been loaded, otherwise returns FALSE
	 * @return boolean TRUE if the user roles have been loaded, otherwise FALSE
	 */
	public function userRolesLoaded()
	{
		return $this->userRolesLoaded;
	}
	
	/**
	 * This method overrides the DatabaseObject::lookup() method to allow the user's roles to be loaded
	 */
	public function lookup()
	{
		parent::lookup();
		if(!$this->userRolesLoaded())
		{
			$userRoleService = UserRoleService::getInstance();
			$userRoles = $userRoleService->getUserRolesForUid($this->getUid());
			$roles = array();
			foreach($userRoles as $role)
			{
				$roles[] = $role->getRid();
			}
			$this->setField('roles', $roles);
			$this->setUserRoles($userRoles);
		}
		$this->passwordEncrypted = TRUE;
	}
	
	/**
	 * This method overrides the DatabaseObject::save() method to allow the user's roles to be saved
	 */
	public function save()
	{
		if(!$this->passwordEncrypted && $this->getPassword() != '')
		{
			$userService = UserService::getInstance();
			$userService->hashUserPassword($this);
		}
		parent::save();
		$roles = $this->getField('roles');
		foreach($this->userRoles as $userRole)
		{
			if(!in_array($userRole->getRid(), $roles))
			{
				UserRoleService::getInstance()->removeRoleFromUser($userRole);
				break;
			}
			else
			{
				if($userRole->getUrid() === NULL)
				{
					if($userRole->getUid() === NULL)
					{
						$userRole->setUid($this->getUid());
					}
					$userRole->save();
				}
			}
		}
		foreach($roles as $rid)
		{
			$hasRole = FALSE;
			foreach($this->userRoles as $userRole)
			{
				if($userRole->getRid() == $rid)
				{
					$hasRole = TRUE;
					break;
				}
			}
			if(!$hasRole)
			{
				UserRoleService::getInstance()->addRoleToUser($this->getUid(), $rid);
			}
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::delete() method to allow the user's roles to be deleted
	 */
	public function delete()
	{
		parent::delete();
		foreach($this->userRoles as $userRole)
		{
			if($userRole->getUrid() != NULL)
			{
				$userRole->delete();
			}
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::setField() method to allow the user's password to be saved correctly
	 */
	public function setField($name, $value)
	{
		if($name == 'password')
		{
			if($value != '')
			{
				$this->passwordEncrypted = FALSE;
				$this->fields[$name] = $value;
			}
		}
		else
		{
			$this->fields[$name] = $value;
		}
	}
}
