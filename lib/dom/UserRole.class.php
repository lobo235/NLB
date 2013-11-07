<?php

$GLOBALS['app']->loadClass('dao', 'DatabaseObject');

/**
 * The UserRole class is a domain level object that holds all the user roles for a particular User
 */
class UserRole extends DatabaseObject {
	public function __construct($urid = NULL) {
		parent::__construct();
		$this->setPrimaryIdColumn('urid');
		$table = new DatabaseTable('user_roles', 'urid');
		$table->addColumn(new DatabaseColumn('urid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('uid', 'hidden,id'));
		$table->addColumn(new DatabaseColumn('rid', 'hidden,id'));
		$this->addTable($table);
		
		if($urid !== NULL)
		{
			$this->setField('urid', $urid);
			$this->lookup();
		}
		else
		{
			$this->setUrid(NULL);
			$this->setUid(NULL);
			$this->setRid(NULL);
		}
	}
	
	/**
	 * This method sets the urid for this UserRole object
	 * @param int $urid The urid for this UserRole object
	 */
	public function setUrid($urid)
	{
		$this->setField('urid', $urid);
	}
	
	/**
	 * This method sets the uid for this UserRole object
	 * @param int $uid The uid for this UserRole object
	 */
	public function setUid($uid)
	{
		$this->setField('uid', $uid);
	}
	
	/**
	 * This method sets the rid for this UserRole object
	 * @param int $rid The rid for this UserRole object
	 */
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	/**
	 * This method gets the urid for this UserRole object
	 * @return int the urid of this UserRole
	 */
	public function getUrid()
	{
		return $this->getField('urid');
	}
	
	/**
	 * This method gets the uid for this UserRole object
	 * @return int the uid of this UserRole
	 */
	public function getUid()
	{
		return $this->getField('uid');
	}
	
	/**
	 * This method returns the rid for this UserRole object
	 * @return int the rid contained in this UserRole object
	 */
	public function getRid()
	{
		return $this->getField('rid');
	}
}
