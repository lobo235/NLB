<?php

class_exists('DatabaseTable') || require_once(NLB_LIB_ROOT.'dao/DatabaseTable.class.php');
class_exists('DatabaseColumn') || require_once(NLB_LIB_ROOT.'dao/DatabaseColumn.class.php');
class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The Entity class serves as a base class for all of the custom objects that will make up the web site/application
 */
class Entity extends DatabaseObject
{
	protected $specialFields;
	/**
	 * The constructor for the Entity class
	 */
	public function __construct($eid = NULL)
	{
		parent::__construct();
		$this->primaryIdColumn = 'eid';
		
		$this->specialFields = array();
		
		$table = new DatabaseTable('entities', 'eid');
		$table->addColumn(new DatabaseColumn('eid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('created_date', 'hidden,datetime,created'));
		$table->addColumn(new DatabaseColumn('modified_date', 'hidden,datetime,modified'));
		$table->addColumn(new DatabaseColumn('uid', 'hidden,id,uid'));
		$table->addColumn(new DatabaseColumn('type', 'hidden,string', 32));
		$table->addColumn(new DatabaseColumn('status', 'radio', NULL, array(
			'radio' => array(
				1 => 'Published',
				0 => 'Unpublished',
			)
		)));
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
	 * Set the field that identifies this Entity
	 * @param string $field the name of the field that identifies this entity
	 */
	public function setIdentifierField($field)
	{
		$this->setField('nlb_identifier', $field);
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
	
	/**
	 * Returns the value of the field that is set as the identifier for this Entity
	 * @return mixed the identifier for this entity
	 */
	public function getIdentifier()
	{
		return $this->getField($this->getField('nlb_identifier'));
	}
	
	/**
	 * This function returns an array of all the special fields
	 * @return DatabaseColumn[] an array of DatabaseColumn objects representing the special fields in the Entity
	 */
	public function getSpecialFields()
	{
		return $this->specialFields;
	}
}
