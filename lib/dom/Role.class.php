<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The Role class is a domain level object that represents a single Role
 */
class Role extends DatabaseObject {
	public function __construct($rid = NULL) {
		parent::__construct();
		$this->setPrimaryIdColumn('rid');
		$table = new DatabaseTable('roles', 'rid');
		$table->addColumn(new DatabaseColumn('rid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('role_name', 'string', 64));
		$this->addTable($table);
		
		if($rid !== NULL)
		{
			$this->setField('rid', $rid);
			$this->lookup();
		}
		else
		{
			$this->setRid(NULL);
			$this->setRoleName(NULL);
		}
	}
	
	/**
	 * This method sets the rid for this Role object
	 * @param int $rid The rid for this Role object
	 */
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	/**
	 * This method sets the role_name for this Role object
	 * @param string $role_name The role_name for this Role object
	 */
	public function setRoleName($role_name)
	{
		$this->setField('role_name', $role_name);
	}
	
	/**
	 * This method returns the rid for this Role object
	 * @return int the rid contained in this Role object
	 */
	public function getRid()
	{
		return $this->getField('rid');
	}
	
	/**
	 * This method returns the role_name for this Role object
	 * @return string the role_name contained in this Role object
	 */
	public function getRoleName()
	{
		return $this->getField('role_name');
	}
}
