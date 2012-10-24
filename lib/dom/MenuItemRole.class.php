<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The MenuItemRole class is a domain level object that holds all the user roles for a particular MenuItem
 */
class MenuItemRole extends DatabaseObject {
	public function __construct($mirid = NULL) {
		parent::__construct();
		$this->setPrimaryIdColumn('mirid');
		$table = new DatabaseTable('menu_item_roles', 'mirid');
		$table->addColumn(new DatabaseColumn('mirid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('miid', 'hidden,id'));
		$table->addColumn(new DatabaseColumn('rid', 'hidden,id'));
		$this->addTable($table);
		
		if($mirid !== NULL)
		{
			$this->setField('mirid', $mirid);
			$this->lookup();
		}
		else
		{
			$this->setMirid(NULL);
			$this->setMiid(NULL);
			$this->setRid(NULL);
		}
	}
	
	/**
	 * This method sets the mirid for this MenuItemRole object
	 * @param int $mirid The mirid for this MenuItemRole object
	 */
	public function setMirid($mirid)
	{
		$this->setField('mirid', $mirid);
	}
	
	/**
	 * This method sets the miid for this MenuItemRole object
	 * @param int $miid The miid for this MenuItemRole object
	 */
	public function setMiid($miid)
	{
		$this->setField('miid', $miid);
	}
	
	/**
	 * This method sets the rid for this MenuItemRole object
	 * @param int $rid The rid for this MenuItemRole object
	 */
	public function setRid($rid)
	{
		$this->setField('rid', $rid);
	}
	
	/**
	 * This method gets the mirid for this MenuItemRole object
	 * @return int the mirid of this MenuItemRole
	 */
	public function getMirid()
	{
		return $this->getField('mirid');
	}
	
	/**
	 * This method gets the miid for this MenuItemRole object
	 * @return int the miid of this MenuItemRole
	 */
	public function getMiid()
	{
		return $this->getField('miid');
	}
	
	/**
	 * This method returns the rid for this MenuItemRole object
	 * @return int the rid contained in this MenuItemRole object
	 */
	public function getRid()
	{
		return $this->getField('rid');
	}
}
