<?php

/**
 * The DatabaseTable class holds table information used by the DatabaseObject class
 */
class DatabaseTable {
	private $tableName;
	private $primaryKeyColumn;
	private $columns;
	
	public function __construct($tableName, $primaryKeyColumn)
	{
		$this->tableName = $tableName;
		$this->primaryKeyColumn = $primaryKeyColumn;
		$this->columns = array();
	}
	
	public function getTableName()
	{
		return $this->tableName;
	}
	
	public function getColumnNames()
	{
		$columnNames = array();
		foreach($this->columns as $column)
		{
			$columnNames[] = $column->getName();
		}
		return $columnNames;
	}
	
	public function addColumn(DatabaseColumn $column)
	{
		$this->columns[$column->getName()] = $column;
	}
	
	public function getColumns()
	{
		return $this->columns;
	}
	
	public function getColumn($columnName)
	{
		return $this->columns[$columnName];
	}
	
	public function getPrimaryKeyColumn()
	{
		return $this->primaryKeyColumn;
	}
}
