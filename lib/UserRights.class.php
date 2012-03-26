<?php

/**
 * The UserRights class is a domain level object that holds all the user rights for a particular User
 */
class UserRights {
	protected $rights;
	
	public function __construct() {
		$this->rights = NULL;
	}
	
	/**
	 * This method sets the rights for this UserRights object
	 * @param array $rights The array of rights that will be used to populate this UserRights object
	 */
	public function setRights(array $rights)
	{
		$this->rights = $rights;
	}
	
	/**
	 * This method returns the rights for this UserRights object
	 * @return array the rights contained in this UserRights object
	 */
	public function getRights()
	{
		return $this->rights;
	}
}
