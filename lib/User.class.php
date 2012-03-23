<?php

require_once(NLB_LIB_ROOT.'Entity.class.php');

/**
 * The User class represents a user of the system
 */
class User extends Entity {
	protected $username;
	protected $password;
	protected $firstName;
	protected $lastName;
	protected $lastLoginDate;
	protected $email;
	
	/**
	 * The constructor for the User class
	 */
	public function __construct()
	{
		parent::__construct();
		$this->type = 'user';
	}
	
	/**
	 * Sets the username for this User
	 * @param string $username the username for this User
	 */
	public function setUsername(string $username)
	{
		$this->username = $username;
	}
	
	/**
	 * Sets the password for this User
	 * @param string $password the password for this User
	 */
	public function setPassword(string $password)
	{
		$this->password = $password;
	}
	
	/**
	 * Sets the first name for this User
	 * @param string $firstName the first name for this User
	 */
	public function setFirstName(string $firstName)
	{
		$this->firstName = $firstName;
	}
	
	/**
	 * Sets the last name for this User
	 * @param string $lastName the last name for this User
	 */
	public function setLastName(string $lastName)
	{
		$this->lastName = $lastName;
	}
	
	/**
	 * Sets the last login date for this User
	 * @param DateTime $lastLoginDate the last login date for this User
	 */
	public function setLastLoginDate(DateTime $lastLoginDate)
	{
		$this->lastLoginDate = $lastLoginDate;
	}
	
	/**
	 * Sets the email address for this User
	 * @param string $email the email address for this User
	 */
	public function setEmail(string $email)
	{
		$this->email = $email;
	}
	
	/**
	 * Returns the username for this User
	 * @return string the username for this User
	 */
	public function getUsername()
	{
		return $this->username;
	}
	
	/**
	 * Returns the password for this User
	 * @return string the password for this User
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	/**
	 * Returns the first name for this User
	 * @return string the first name for this User
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	/**
	 * Returns the last name for this User
	 * @return string the last name for this User
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	
	/**
	 * Returns the last login date for this User
	 * @return DateTime the last login date for this User
	 */
	public function getLastLoginDate()
	{
		return $this->lastLoginDate;
	}
	
	/**
	 * Returns the email address for this User
	 * @return string the email address for this User
	 */
	public function getEmail()
	{
		return $this->email;
	}
}
