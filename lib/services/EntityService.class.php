<?php

class_exists('DatabaseService') || require(NLB_LIB_ROOT.'services/DatabaseService.class.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntityService
 *
 * @author lobo235
 */
class EntityService
{
	private static $instance;
	private $DB;
	
	/**
	 * The constructor for the EntityService class
	 * @return EntityService
	 */
	private function __construct()
	{
		$this->DB = DatabaseService::getInstance();
	}

	/**
	 * This declaration of a private __clone method helps enforce the singleton pattern
	 */
	private function __clone() { }

	/**
	 * Returns an instance of the EntityService class
	 * @return EntityService 
	 */
	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new EntityService();
		}

		return self::$instance;
	}
	
	/**
	 * This method retrieves the Entities for this site
	 * @param string entity type
	 * @return Entity[]
	 */
	public function getEntities($type = NULL)
	{
		$entities = array();
		if($type == NULL)
		{
			$query = "SELECT `eid`,`type` FROM `entities`";
			$res = $this->DB->getSelectArray($query);
		}
		else
		{
			$query = "SELECT `eid`,`type` FROM `entities` WHERE `type` = ?";
			$res = $this->DB->getSelectArray($query, $type);
		}
		
		if(is_array($res) && count($res) > 0)
		{
			foreach($res as $row)
			{
				class_exists($row['type']) || require(NLB_LIB_ROOT.'dom/'.$row['type'].'.class.php');
				$entity = new $row['type']();
				$entity->lookupUsingEid($row['eid']);
				$entities[] = $entity;
			}
		}
		return $entities;
	}
}