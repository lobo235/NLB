<?php

class_exists('DatabaseObject') || require_once(NLB_LIB_ROOT.'dao/DatabaseObject.class.php');
class_exists('MenuService') || require_once(NLB_LIB_ROOT.'services/MenuService.class.php');
class_exists('UserRoleService') || require_once(NLB_LIB_ROOT.'services/UserRoleService.class.php');

/**
 * The MenuItem class is a domain level object that represents a single MenuItem
 */
class MenuItem extends DatabaseObject {
	protected $menuItemRolesLoaded;
	protected $menuItemRoles;
	
	public function __construct($miid = NULL) {
		parent::__construct();
		$this->setPrimaryIdColumn('miid');
		$table = new DatabaseTable('menu_items', 'miid');
		$table->addColumn(new DatabaseColumn('miid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('mid', 'hidden,id'));
		$table->addColumn(new DatabaseColumn('menu_item_name', 'string', 128));
		$table->addColumn(new DatabaseColumn('menu_item_location', 'string', 255));
		$this->addTable($table);
		
		$this->specialFields[] = new DatabaseColumn('mid', 'select', NULL, array(
			'label' => 'Menu',
			'size' => 1,
			'options' => MenuService::getInstance()->getAvailableMenus(),
		));
		
		$this->specialFields[] = new DatabaseColumn('roles', 'select,multiple', NULL, array(
			'size' => 7,
			'options' => UserRoleService::getInstance()->getAvailableUserRoles(),
		));
		
		$this->menuItemRolesLoaded = FALSE;
		
		if($miid !== NULL)
		{
			$this->setField('miid', $miid);
			$this->lookup();
		}
		else
		{
			$this->setMiid(NULL);
			$this->setMenuItemName(NULL);
		}
	}
	
	/**
	 * This method sets the miid for this MenuItem object
	 * @param int $miid The miid for this MenuItem object
	 */
	public function setMiid($miid)
	{
		$this->setField('miid', $miid);
	}
	
	/**
	 * This method sets the mid for this MenuItem object
	 * @param int $mid The mid for this MenuItem object
	 */
	public function setMid($mid)
	{
		$this->setField('mid', $mid);
	}
	
	/**
	 * This method sets the menu_item_name for this MenuItem object
	 * @param string $menu_item_name The menu_item_name for this MenuItem object
	 */
	public function setMenuItemName($menu_item_name)
	{
		$this->setField('menu_item_name', $menu_item_name);
	}
	
	/**
	 * This method sets the menu_item_location for this MenuItem object
	 * @param string $menu_item_location The menu_item_location for this MenuItem object
	 */
	public function setMenuItemLocation($menu_item_location)
	{
		$this->setField('menu_item_location', $menu_item_location);
	}
	
	/**
	 * This method returns the miid for this MenuItem object
	 * @return int the miid contained in this MenuItem object
	 */
	public function getMiid()
	{
		return $this->getField('miid');
	}
	
	/**
	 * This method returns the mid for this MenuItem object
	 * @return int the mid contained in this MenuItem object
	 */
	public function getMid()
	{
		return $this->getField('mid');
	}
	
	/**
	 * This method returns the menu_item_name for this MenuItem object
	 * @return string the menu_item_name contained in this MenuItem object
	 */
	public function getMenuItemName()
	{
		return $this->getField('menu_item_name');
	}
	
	/**
	 * This method returns the menu_item_location for this MenuItem object
	 * @return string the menu_item_location contained in this MenuItem object
	 */
	public function getMenuItemLocation()
	{
		return $this->getField('menu_item_location');
	}
	
	public function menuItemRolesLoaded()
	{
		return $this->menuItemRolesLoaded;
	}
	
	/**
	 * Sets the menu item roles for this MenuItem
	 * @param MenuItemRole[] $menuItemRoles an array of MenuItemRole objects for this MenuItem
	 */
	public function setMenuItemRoles(array $menuItemRoles)
	{
		$this->menuItemRoles = $menuItemRoles;
		$this->menuItemRolesLoaded = TRUE;
	}
	
	/**
	 * Returns the menu item roles for this MenuItem
	 * @return MenuItemRole[] the array of MenuItemRole objects for this MenuItem
	 */
	public function getMenuItemRoles()
	{
		return $this->menuItemRoles;
	}
	
	/**
	 * This method overrides the DatabaseObject::lookup() method to allow the menu_item's roles to be loaded
	 */
	public function lookup()
	{
		parent::lookup();
		if(!$this->menuItemRolesLoaded())
		{
			$menuItemRoles = UserRoleService::getInstance()->getRolesForMiid($this->getMiid());
			$roles = array();
			foreach($menuItemRoles as $role)
			{
				$roles[] = $role->getRid();
			}
			$this->setField('roles', $roles);
			$this->setMenuItemRoles($menuItemRoles);
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::save() method to allow the menu_item's roles to be saved
	 */
	public function save()
	{
		parent::save();
		$roles = $this->getField('roles');
		if(is_array($this->getMenuItemRoles()))
		{
			foreach($this->getMenuItemRoles() as $menuItemRole)
			{
				if(!in_array($menuItemRole->getRid(), $roles))
				{
					$menuItemRole->delete();
					break;
				}
				else
				{
					if($menuItemRole->getMirid() === NULL)
					{
						if($menuItemRole->getMiid() === NULL)
						{
							$menuItemRole->setMiid($this->getMiid());
						}
						$menuItemRole->save();
					}
				}
			}
		}
		foreach($roles as $rid)
		{
			$hasRole = FALSE;
			if(is_array($this->getMenuItemRoles()))
			{
				foreach($this->getMenuItemRoles() as $menuItemRole)
				{
					if($menuItemRole->getRid() == $rid)
					{
						$hasRole = TRUE;
						break;
					}
				}
			}
			if(!$hasRole)
			{
				$menuItemRole = new MenuItemRole();
				$menuItemRole->setMiid($this->getMiid());
				$menuItemRole->setRid($rid);
				$menuItemRole->save();
			}
		}
	}
	
	/**
	 * This method overrides the DatabaseObject::delete() method to allow the menu_item's roles to be deleted
	 */
	public function delete()
	{
		parent::delete();
		foreach($this->menuItemRoles as $menuItemRole)
		{
			if($menuItemRole->getMirid() != NULL)
			{
				$menuItemRole->delete();
			}
		}
	}
}
