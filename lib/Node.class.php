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
		$table->addColumn(new DatabaseColumn('title', 'string', 255, array(
			'size' => 40,
		)));
		$table->addColumn(new DatabaseColumn('body', 'text', 65536, array(
			'rows' => 6,
			'cols' => 80,
		)));
		$this->addTable($table);
		
		$this->setType('node');
		
		if($nid !== NULL)
		{
			$this->setField('nid', $nid);
			$this->lookup();
		}
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
}