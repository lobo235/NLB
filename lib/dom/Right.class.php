<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The Right class is a domain level object that represents a single Right
 */
class Right extends DatabaseObject {
	public function __construct($rid = NULL) {
		parent::__construct();
		$this->primaryIdColumn = 'rid';
		$table = new DatabaseTable('rights', 'rid');
		$table->addColumn(new DatabaseColumn('rid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('right_name', 'string', 64));
		$this->addTable($table);
		
		if($rid !== NULL)
		{
			$this->setField('rid', $rid);
			$this->lookup();
		}
		else
		{
			$this->setRid(NULL);
			$this->setRightName(NULL);
		}
	}
	
	/**
	 * This method sets the rid for this Right object
	 * @param int $rid The rid for this Right object
	 */
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	/**
	 * This method sets the right_name for this Right object
	 * @param string $right_name The right_name for this Right object
	 */
	public function setRightName($right_name)
	{
		$this->setField('right_name', $right_name);
	}
	
	/**
	 * This method returns the rid for this Right object
	 * @return int the rid contained in this Right object
	 */
	public function getRid()
	{
		return $this->getField('rid');
	}
	
	/**
	 * This method returns the right_name for this Right object
	 * @return string the right_name contained in this Right object
	 */
	public function getRightName()
	{
		return $this->getField('right_name');
	}
}
