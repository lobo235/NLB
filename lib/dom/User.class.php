<?php

class_exists('DatabaseTable') || require(NLB_LIB_ROOT.'dao/DatabaseTable.class.php');
class_exists('DatabaseColumn') || require(NLB_LIB_ROOT.'dao/DatabaseColumn.class.php');
class_exists('Entity') || require(NLB_LIB_ROOT.'dom/Entity.class.php');
class_exists('UserRightService') || require(NLB_LIB_ROOT.'services/UserRightService.class.php');

/**
 * The User class represents a user of the system
 */
class User extends Entity {
	protected $userRights;
	protected $userRightsLoaded;
	
	/**
	 * The constructor for the User class
	 */
	public function __construct($uid = NULL)
	{
		parent::__construct();
		$this->primaryIdColumn = 'uid';
		
		$table = new DatabaseTable('users', 'uid');
		$table->addColumn(new DatabaseColumn('uid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('username', 'string', 32));
		$table->addColumn(new DatabaseColumn('password', 'string,password', 32));
		$table->addColumn(new DatabaseColumn('first_name', 'string', 32));
		$table->addColumn(new DatabaseColumn('last_name', 'string', 32));
		$table->addColumn(new DatabaseColumn('last_login_date', 'hidden,datetime'));
		$table->addColumn(new DatabaseColumn('email', 'string', 64));
		$this->addTable($table);
		
		$this->setType('User');
		
		if($uid !== NULL)
		{
			$this->setField('uid', $uid);
			$this->lookup();
		}
		else
		{
			$this->setLastLoginDate(NULL);
		}
		
		$this->userRightsLoaded = FALSE;
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
	 * Sets the user rights for this User
	 * @param UserRight[] $userRights an array of UserRight objects for this User
	 */
	public function setUserRights(array $userRights)
	{
		$this->userRights = $userRights;
		$this->userRightsLoaded = TRUE;
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
	 * Returns the user rights for this User
	 * @return UserRight[] the array of UserRight objects for this User
	 */
	public function getUserRights()
	{
		return $this->userRights;
	}
	
	/**
	 * Returns TRUE if the user rights have been loaded, otherwise returns FALSE
	 * @return boolean TRUE if the user rights have been loaded, otherwise FALSE
	 */
	public function userRightsLoaded()
	{
		return $this->userRightsLoaded;
	}
	
	/**
	 * This method overrides the DatabaseObject::lookup() method to allow the user's rights to be loaded
	 */
	public function lookup()
	{
		parent::lookup();
		if(!$this->userRightsLoaded)
		{
			$userRightService = UserRightService::getInstance();
			$userRights = $userRightService->getUserRightsForUid($this->getUid());
			$this->setUserRights($userRights);
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::save() method to allow the user's rights to be saved
	 */
	public function save()
	{
		parent::save();
		foreach($this->userRights as $userRight)
		{
			if($userRight->getUrid() == NULL)
			{
				if($userRight->getUid() == NULL)
				{
					$userRight->setUid($this->getUid());
				}
				$userRight->save();
			}
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::delete() method to allow the user's rights to be deleted
	 */
	public function delete()
	{
		parent::delete();
		foreach($this->userRights as $userRight)
		{
			if($userRight->getUrid() != NULL)
			{
				$userRight->delete();
			}
		}
	}
}