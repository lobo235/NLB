<?php

class_exists('DatabaseObject') || require(NLB_LIB_ROOT.'DatabaseObject.class.php');

/**
 * The UserRight class is a domain level object that holds all the user rights for a particular User
 */
class UserRight extends DatabaseObject {
	public function __construct($rid = NULL) {
		parent::__construct();
		$this->primaryIdColumn = 'rid';
		$table = new DatabaseTable('user_rights', 'rid');
		$table->addColumn(new DatabaseColumn('rid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('uid', 'hidden,id'));
		$table->addColumn(new DatabaseColumn('right', 'hidden,string', 64));
		$this->addTable($table);
		
		if($rid !== NULL)
		{
			$this->setField('rid', $rid);
			$this->lookup();
		}
		else
		{
			$this->setRid(NULL);
			$this->setRight(NULL);
			$this->setUid(NULL);
		}
	}
	
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	public function setUid($uid)
	{
		$this->setField('uid', $uid);
	}
	
	/**
	 * This method sets the right for this UserRight object
	 * @param string $right The right for this this UserRight object
	 */
	public function setRight($right)
	{
		$this->setField('right', $right);
	}
	
	/**
	 * This method returns the right for this UserRight object
	 * @return string the right contained in this UserRight object
	 */
	public function getRight()
	{
		return $this->getField('right');
	}
}
