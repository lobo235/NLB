<?php

class Entity
{
	private $eid;
	private $createdDate;
	private $modifiedDate;
	private $uid;
	private $type;
	private $status;

	public function __construct()
	{
		$this->eid = NULL;
		$this->createdDate = NULL;
		$this->modifiedDate = NULL;
		$this->uid = NULL;
		$this->type = NULL;
		$this->status = NULL;
	}

	public function setEid($eid)
	{
		$this->eid = $eid;
	}

	public function setCreatedDate($createdDate)
	{
		$this->createdDate = $createdDate;
	}

	public function setModifiedDate($modifiedDate)
	{
		$this->modifiedDate = $modifiedDate;
	}

	public function setUid($uid)
	{
		$this->uid = $uid;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function getEid()
	{
		return $this->eid;
	}

	public function getCreatedDate()
	{
		return $this->createdDate;
	}

	public function getModifiedDate()
	{
		return $this->modifiedDate;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getStatus()
	{
		return $this->status;
	}
}
