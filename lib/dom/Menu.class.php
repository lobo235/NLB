<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');

/**
 * The Menu class is a domain level object that represents a single Menu
 */
class Menu extends DatabaseObject {
	public function __construct($mid = NULL) {
		parent::__construct();
		$this->setPrimaryIdColumn('mid');
		$table = new DatabaseTable('menus', 'mid');
		$table->addColumn(new DatabaseColumn('mid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('menu_name', 'string', 128));
		$this->addTable($table);
		
		if($mid !== NULL)
		{
			$this->setField('mid', $mid);
			$this->lookup();
		}
		else
		{
			$this->setMid(NULL);
			$this->setMenuName(NULL);
		}
	}
	
	/**
	 * This method sets the mid for this Menu object
	 * @param int $mid The mid for this Menu object
	 */
	public function setMid($mid)
	{
		$this->setField('mid', $mid);
	}
	
	/**
	 * This method sets the menu_name for this Menu object
	 * @param string $menu_name The menu_name for this Menu object
	 */
	public function setMenuName($menu_name)
	{
		$this->setField('menu_name', $menu_name);
	}
	
	/**
	 * This method returns the mid for this Menu object
	 * @return int the mid contained in this Menu object
	 */
	public function getMid()
	{
		return $this->getField('mid');
	}
	
	/**
	 * This method returns the menu_name for this Menu object
	 * @return string the menu_name contained in this Menu object
	 */
	public function getMenuName()
	{
		return $this->getField('menu_name');
	}
}
