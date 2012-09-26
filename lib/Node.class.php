<?php

class_exists('DatabaseTable') || require(NLB_LIB_ROOT.'DatabaseTable.class.php');
class_exists('DatabaseColumn') || require(NLB_LIB_ROOT.'DatabaseColumn.class.php');
class_exists('Entity') || require(NLB_LIB_ROOT.'Entity.class.php');

/**
 * The Node class represents a single Node in the system
 */
class Node extends Entity {
	/**
	 * The constructor for the Node class
	 */
	public function __construct($nid = NULL)
	{
		parent::__construct();
		$this->primaryIdColumn = 'nid';
		
		$table = new DatabaseTable('nodes', 'nid');
		$table->addColumn(new DatabaseColumn('nid', 'hidden,primary,id'));
		$table->addColumn(new DatabaseColumn('title', 'string', 255));
		$table->addColumn(new DatabaseColumn('body', 'text'));
		$this->addTable($table);
		
		$this->setType('node');
		
		if($nid !== NULL)
		{
			$this->setField('nid', $nid);
			$this->lookup();
		}
	}
}