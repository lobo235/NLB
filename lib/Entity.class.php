<?php

class_exists('DatabaseTable') || require(NLB_LIB_ROOT.'DatabaseTable.class.php');
class_exists('DatabaseColumn') || require(NLB_LIB_ROOT.'DatabaseColumn.class.php');
class_exists('DatabaseObject') || require(NLB_LIB_ROOT.'DatabaseObject.class.php');

/**
 * The Entity class serves as a base class for all of the custom objects that will make up the web site/application
 */
class Entity extends DatabaseObject
{

	/**
	 * The constructor for the Entity class
	 */
	public function __construct($eid = NULL)
	{
		parent::__construct();
		$this->primaryIdColumn = 'eid';
		
		$table = new DatabaseTable('entities', 'eid');
		$table->addColumn(new DatabaseColumn('eid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('created_date', 'hidden,datetime,created'));
		$table->addColumn(new DatabaseColumn('modified_date', 'hidden,datetime,modified'));
		$table->addColumn(new DatabaseColumn('uid', 'hidden,id'));
		$table->addColumn(new DatabaseColumn('type', 'hidden,string', 32));
		$table->addColumn(new DatabaseColumn('status', 'hidden,boolean', NULL, 'radio|1:Published|0:Unpublished'));
		$this->addTable($table);
		
		if($eid !== NULL)
		{
			$this->setField('eid', $eid);
			$this->lookup();
		}
		else
		{
			$this->setEid(NULL);
			$this->setCreatedDate(NULL);
			$this->setModifiedDate(NULL);
			$this->setStatus(0);
			$this->setType(NULL);
			$this->setUid(0);
		}
	}

	/**
	 * Set the eid for this Entity
	 * @param int $eid the entity id 
	 */
	public function setEid($eid)
	{
		$this->setField('eid', $eid);
	}

	/**
	 * Set the createdDate for this Entity
	 * @param DateTime $createdDate the date this entity was created
	 */
	public function setCreatedDate($createdDate)
	{
		$this->setField('created_date', $createdDate);
	}

	/**
	 * Set the modifiedDate for this Entity
	 * @param DateTime $modifiedDate the date this entity was last modified
	 */
	public function setModifiedDate($modifiedDate)
	{
		$this->setField('modified_date', $modifiedDate);
	}

	/**
	 * Set the uid for this Entity
	 * @param int $uid the uid of the user who created this entity
	 */
	public function setUid($uid)
	{
		$this->setField('uid', $uid);
	}

	/**
	 * Set the type for this Entity
	 * @param string $type the type of this entity
	 */
	public function setType($type)
	{
		$this->setField('type', $type);
	}

	/**
	 * Set the status for this Entity
	 * @param int $status the status of this entity
	 */
	public function setStatus($status)
	{
		$this->setField('status', $status);
	}

	/**
	 * Returns the eid of this Entity
	 * @return int the eid for this Entity 
	 */
	public function getEid()
	{
		return $this->getField('eid');
	}

	/**
	 * Returns the createdDate of this Entity
	 * @return DateTime the date this Entity was created 
	 */
	public function getCreatedDate()
	{
		return $this->getField('createdDate');
	}

	/**
	 * Returns the modifiedDate of this Entity
	 * @return DateTime the date this Entity was last modified
	 */
	public function getModifiedDate()
	{
		return $this->getField('modifiedDate');
	}

	/**
	 * Returns the uid or the user who created this Entity
	 * @return int the uid for this Entity
	 */
	public function getUid()
	{
		return $this->getField('uid');
	}

	/**
	 * Returns the type of this Entity
	 * @return string the type of this Entity
	 */
	public function getType()
	{
		return $this->getField('type');
	}

	/**
	 * Returns the status of this Entity
	 * @return int the status of this Entity
	 */
	public function getStatus()
	{
		return $this->getField('status');
	}
}
