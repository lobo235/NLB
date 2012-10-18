<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The UserRight class is a domain level object that holds all the user rights for a particular User
 */
class UserRight extends DatabaseObject {
	public function __construct($urid = NULL) {
		parent::__construct();
		$this->primaryIdColumn = 'urid';
		$table = new DatabaseTable('user_rights', 'urid');
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
	 * This method sets the urid for this UserRight object
	 * @param int $urid The urid for this UserRight object
	 */
	public function setUrid($urid)
	{
		$this->setField('urid', $urid);
	}
	
	/**
	 * This method sets the uid for this UserRight object
	 * @param int $uid The uid for this UserRight object
	 */
	public function setUid($uid)
	{
		$this->setField('uid', $uid);
	}
	
	/**
	 * This method sets the rid for this UserRight object
	 * @param int $rid The rid for this UserRight object
	 */
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	/**
	 * This method gets the urid for this UserRight object
	 * @return int the urid of this UserRight
	 */
	public function getUrid()
	{
		return $this->getField('urid');
	}
	
	/**
	 * This method gets the uid for this UserRight object
	 * @return int the uid of this UserRight
	 */
	public function getUid()
	{
		return $this->getField('uid');
	}
	
	/**
	 * This method returns the rid for this UserRight object
	 * @return int the rid contained in this UserRight object
	 */
	public function getRid()
	{
		return $this->getField('rid');
	}
}
