<?php

class_exists('DatabaseTable') || require_once(NLB_LIB_ROOT.'dao/DatabaseTable.class.php');
class_exists('DatabaseColumn') || require_once(NLB_LIB_ROOT.'dao/DatabaseColumn.class.php');
class_exists('Entity') || require_once(NLB_LIB_ROOT.'dom/Entity.class.php');
class_exists('UrlAliasService') || require_once(NLB_LIB_ROOT.'services/UrlAliasService.class.php');

/**
 * The Node class represents a single Node in the system
 */
class Node extends Entity {
	protected $alias;
	
	/**
	 * The constructor for the Node class
	 */
	public function __construct($nid = NULL)
	{
		parent::__construct();
		$this->setPrimaryIdColumn('nid');
		
		$table = new DatabaseTable('nodes', 'nid');
		$table->addColumn(new DatabaseColumn('nid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('title', 'string', 255, array(
			'size' => 40,
		)));
		$table->addColumn(new DatabaseColumn('body', 'text,wysiwyg', 65536, array(
			'rows' => 6,
			'cols' => 80,
		)));
		$this->addTable($table);
		
		$this->specialFields[] = new DatabaseColumn('alias', 'string', 255, array(
			'size' => 60,
		));
		
		$this->setType('Node');
		
		if($nid !== NULL)
		{
			$this->setField('nid', $nid);
			$this->lookup();
		}
		$this->setIdentifierField('title');
	}

	/**
	 * Set the nid for this Node
	 * @param int $nid the node nid
	 */
	public function setNid($nid)
	{
		$this->setField('nid', $nid);
	}

	/**
	 * Set the title for this Node
	 * @param string $title the title of this node
	 */
	public function setTitle($title)
	{
		$this->setField('title', $title);
	}

	/**
	 * Set the body for this Node
	 * @param string $body the body of this node
	 */
	public function setBody($body)
	{
		$this->setField('body', $body);
	}

	/**
	 * Returns the nid of this Node
	 * @return int the nid for this Node
	 */
	public function getNid()
	{
		return $this->getField('nid');
	}

	/**
	 * Returns the title of this Node
	 * @return string the title of this Node
	 */
	public function getTitle()
	{
		return $this->getField('title');
	}

	/**
	 * Returns the body of this Node
	 * @return string the body of this Node
	 */
	public function getBody()
	{
		return $this->getField('body');
	}
	
	/**
	 * This function overrides the lookup method in DatabaseObject in order to provide additional functionality
	 */
	public function lookup()
	{
		parent::lookup();
		$path = 'node/'.$this->getNid();
		$alias = UrlAliasService::getInstance()->getAlias($path);
		if($alias != $path)
		{
			$this->setField('alias', $alias);
		}
	}
	
	/**
	 * This function overrides the save method in DatabaseObject in order to provide additional functionality
	 */
	public function save()
	{
		parent::save();
		if($this->getField('alias') != '')
			UrlAliasService::getInstance()->setAlias('node/'.$this->getNid(), $this->getField('alias'));
		else
			UrlAliasService::getInstance()->deleteAliasForPath('node/'.$this->getNid());
	}
	
	/**
	 * This function overrides the delete method in DatabaseObject in order to provice additional functionality
	 */
	public function delete()
	{
		parent::delete();
		if($this->getField('alias') != '')
			UrlAliasService::getInstance()->deleteAliasForPath('node/'.$this->getNid());
	}
}