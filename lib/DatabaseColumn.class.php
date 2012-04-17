<?php

/**
 * The DatabaseColumn describes a column used by the DatabaseTable class
 */
class DatabaseColumn {
	private $name;
	private $type;
	private $maxlength;
	private $extras;
	
	public function __construct($name, $type = NULL, $maxlength = NULL, $extras = NULL)
	{
		$this->name = $name;
		$this->type = $type;
		$this->maxlength = $maxlength;
		$this->extras = $extras;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getMaxLength()
	{
		return $this->maxlength;
	}
	
	public function getExtras()
	{
		return $this->extras;
	}
	
	public function isType($type)
	{
		return(strpos(','.$this->type.',', ','.$type.',') !== FALSE);
	}
}
