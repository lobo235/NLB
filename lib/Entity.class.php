<?php

/**
 * The Entity class serves as a base class for all of the custom objects that will make up the web site/application
 */
class Entity
{
	private $eid;
	private $createdDate;
	private $modifiedDate;
	private $uid;
	private $type;
	private $status;

	/**
	 * The constructor for the Entity class
	 */
	public function __construct()
	{
		$this->eid = NULL;
		$this->createdDate = NULL;
		$this->modifiedDate = NULL;
		$this->uid = NULL;
		$this->type = NULL;
		$this->status = NULL;
	}

	/**
	 * Set the eid for this Entity
	 * @param int $eid the entity id 
	 */
	public function setEid(int $eid)
	{
		$this->eid = $eid;
	}

	/**
	 * Set the createdDate for this Entity
	 * @param DateTime $createdDate the date this entity was created
	 */
	public function setCreatedDate(DateTime $createdDate)
	{
		$this->createdDate = $createdDate;
	}

	/**
	 * Set the modifiedDate for this Entity
	 * @param DateTime $modifiedDate the date this entity was last modified
	 */
	public function setModifiedDate(DateTime $modifiedDate)
	{
		$this->modifiedDate = $modifiedDate;
	}

	/**
	 * Set the uid for this Entity
	 * @param int $uid the uid of the user who created this entity
	 */
	public function setUid(int $uid)
	{
		$this->uid = $uid;
	}

	/**
	 * Set the type for this Entity
	 * @param string $type the type of this entity
	 */
	public function setType(string $type)
	{
		$this->type = $type;
	}

	/**
	 * Set the status for this Entity
	 * @param int $status the status of this entity
	 */
	public function setStatus(int $status)
	{
		$this->status = $status;
	}

	/**
	 * Returns the eid of this Entity
	 * @return int the eid for this Entity 
	 */
	public function getEid()
	{
		return $this->eid;
	}

	/**
	 * Returns the createdDate of this Entity
	 * @return DateTime the date this Entity was created 
	 */
	public function getCreatedDate()
	{
		return $this->createdDate;
	}

	/**
	 * Returns the modifiedDate of this Entity
	 * @return DateTime the date this Entity was last modified
	 */
	public function getModifiedDate()
	{
		return $this->modifiedDate;
	}

	/**
	 * Returns the uid or the user who created this Entity
	 * @return int the uid for this Entity
	 */
	public function getUid()
	{
		return $this->uid;
	}

	/**
	 * Returns the type of this Entity
	 * @return string the type of this Entity
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Returns the status of this Entity
	 * @return int the status of this Entity
	 */
	public function getStatus()
	{
		return $this->status;
	}
}
