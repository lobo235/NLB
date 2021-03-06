<?php

$GLOBALS['app']->loadClass('dao', 'DatabaseTable');
$GLOBALS['app']->loadClass('services', 'LogService');
$GLOBALS['app']->loadClass('services', 'DatabaseService');
$GLOBALS['app']->loadClass('exceptions', 'DatabaseObjectException');

class DatabaseObject
{
	protected $tables;
	protected $fields;
	protected $primaryIdColumn = NULL;
	protected $Log;
	protected $DB;
	protected $specialFields;

	public function __construct()
	{
		$this->tables = array();
		$this->fields = array();
		$this->Log = LogService::getInstance();
		$this->DB = DatabaseService::getInstance();
		$this->specialFields = array();
	}

	public function lookup()
	{
		if($this->getField($this->getPrimaryIdColumn()) === NULL)
		{
			$this->Log->error('DatabaseObject->lookup()', 'Method was called when primaryId not set');
		}
		foreach(array_reverse($this->tables) as $table)
		{
			$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
			$res = NULL;
			try
			{
				$res = $this->DB->getSelectArray($q, $this->getField($table->getPrimaryKeyColumn()));
			}
			catch(DatabaseServiceException $e)
			{
				$this->Log->error('DatabaseObject->lookup()', $e->getCode().' : '.$e->getMessage()."\nQuery: $q\nParam: ".$this->getField($table->getPrimaryKeyColumn()));
			}
			if($res && count($res) > 0)
			{
				if(isset($res[0][$this->getPrimaryIdColumn()]))
				{
					unset($res[0][$this->getPrimaryIdColumn()]);
				}

				$this->fields = array_merge($this->fields, $res[0]);
			}
		}
	}

	/**
	 * This method saves the DatabaseObject to the Database
	 * @throws DatabaseObjectException if there was a problem saving this DatabaseObject to the Database
	 */
	public function save()
	{
		if($this->getField($this->getPrimaryIdColumn()) === NULL) // We will insert new data into the DB
		{
			$this->insert();
		}
		else // We have a primary key so update the existing data in the DB if it exists, otherwise, do an insert
		{
			if($this->exists())
			{
				$this->update();
			}
			else
			{
				$this->insert();
			}
		}
	}
	
	private function insert()
	{
		$insertId = FALSE;
		$lastPrimaryKeyColumn = NULL;

		$currentDateTime = date('Y-m-d H:i:s');
		
		$success = TRUE;
		if(count($this->tables) > 1)
		{
			$this->DB->beginTransaction();
		}

		foreach($this->tables as $tableKey => $table)
		{
			$columnNames = $table->getColumnNames();
			$columnValues = array();
			foreach($columnNames as $key => $columnName)
			{
				$column = $table->getColumn($columnName);
				$columnNames[$key] = "`$columnName`";
				if($column->isType('created') || $column->isType('modified'))
				{
					$columnValues[] = $currentDateTime;
				}
				else
				{
					$columnValues[] = $this->getField($columnName);
				}
			}

			if($insertId !== FALSE) // We have a foreign key from a parent table to use in this table
			{
				$columnNames[] = "`$lastPrimaryKeyColumn`";
				$columnValues[] = $insertId;
			}

			$q = "INSERT INTO `".$table->getTableName()."` (".implode(', ', $columnNames).") VALUES (".trim(str_repeat('?,', count($columnValues)), ',').")";
			try
			{
				$insertId = $this->DB->execUpdate($q, $columnValues);
			}
			catch(DatabaseServiceException $e)
			{
				$success = FALSE;
				if($e->getCode() == DatabaseServiceException::QUERY_ERROR_UNIQUE)
				{
					throw new DatabaseObjectException('Unique Key Violation', DatabaseObjectException::UNIQUE_ERROR);
				}
				else
				{
					throw new DatabaseObjectException('Unspecified error', DatabaseObjectException::UNSPECIFIED_ERROR);
				}
			}

			$lastPrimaryKeyColumn = $table->getPrimaryKeyColumn();

			$this->setField($table->getPrimaryKeyColumn(), $insertId);
		}

		if(count($this->tables) > 1)
		{
			if($success)
			{
				$this->DB->commit();
			}
			else
			{
				$this->DB->rollBack();
			}
		}
	}
	
	private function update()
	{
		$currentDateTime = date('Y-m-d H:i:s');

		foreach($this->tables as $table)
		{
			$columnValues = array();
			$columnUpdateClauses = array();
			foreach($table->getColumnNames() as $key => $columnName)
			{
				$column = $table->getColumn($columnName);
				if(!$column->isType('primary'))
				{
					if($column->isType('modified'))
					{
						$columnValues[] = $currentDateTime;
					}
					else
					{
						$columnValues[] = $this->getField($columnName);
					}
					$columnUpdateClauses[] = "`$columnName` = ?";
				}
			}

			$columnValues[] = $this->getField($table->getPrimaryKeyColumn());

			$q = "UPDATE `".$table->getTableName()."` SET ".implode(', ', $columnUpdateClauses)." WHERE `".$table->getPrimaryKeyColumn()."` = ?";
			$this->DB->execUpdate($q, $columnValues);
		}
	}
	
	private function exists()
	{
		$primaryIdColumn = end($this->tables)->getPrimaryKeyColumn();
		$tableName = end($this->tables)->getTableName();
		$query = "SELECT `".$primaryIdColumn."` FROM `".$tableName."` WHERE `".$primaryIdColumn."` = ?";
		$res = $this->DB->getSelectFirst($query, $this->getField($primaryIdColumn));
		if($res === $this->getField($primaryIdColumn))
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Deletes the current DatabaseObject from the Database
	 * @throws DatabaseObjectException 
	 */
	public function delete()
	{
		if($this->getField($this->getPrimaryIdColumn()) != NULL) // We can only delete an object that has a primaryId set
		{
			if(count($this->tables) > 1)
			{
				$this->DB->beginTransaction();
			}
			
			$success = TRUE;
			
			foreach($this->tables as $table)
			{
				$q = "DELETE FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
				try
				{
					$this->DB->execUpdate($q, $this->getField($table->getPrimaryKeyColumn()));
				}
				catch(DatabaseServiceException $e)
				{
					$success = FALSE;
					throw new DatabaseObjectException('Unspecified error', DatabaseObjectException::UNSPECIFIED_ERROR);
				}
			}
			
			if(count($this->tables) > 1)
			{
				if($success)
				{
					$this->DB->commit();
				}
				else
				{
					$this->DB->rollBack();
				}
			}
		}
	}

	public function setPrimaryId($id)
	{
		$this->setField($this->primaryIdColumn, $id);
	}

	protected function setPrimaryIdColumn($name)
	{
		$this->primaryIdColumn = $name;
	}
	
	public function getPrimaryIdColumn()
	{
		return $this->primaryIdColumn;
	}

	protected function addTable(DatabaseTable $databaseTable)
	{
		$this->tables[] = $databaseTable;
	}

	public function getField($name)
	{
		if(isset($this->fields[$name]))
			return $this->fields[$name];
		else
			return NULL;
	}

	public function setField($name, $value)
	{
		$this->fields[$name] = $value;
	}
	
	public function getColumns()
	{
		$allcolumns = array();
		foreach($this->tables as $table)
		{
			$columns = array();
			foreach($table->getColumns() as $column)
				$columns[] = $column;
			
			$allcolumns[$table->getTableName()] = $columns;
		}
		return $allcolumns;
	}
	/* This was the old way we did it. The new way is below this commented block. Saving this code in case the new method breaks something.
	public function lookupUsingEidOld($eid = NULL)
	{
		if($eid === NULL)
		{
			$this->Log->error('DatabaseObject->lookupUsingEid()', 'Method was called with NULL eid');
		}
		else
		{
			$this->setField('eid', $eid);
		}
		$i = 0;
		foreach($this->tables as $table)
		{
			if($i == 0)
			{
				$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
				$res = $this->DB->getSelectArray($q, $this->getField($table->getPrimaryKeyColumn()));
			}
			else
			{
				$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$this->tables[$i-1]->getPrimaryKeyColumn()."` = ?";
				$res = $this->DB->getSelectArray($q, $this->getField($this->tables[$i-1]->getPrimaryKeyColumn()));
			}
			
			if($res && count($res) > 0)
			{
				$this->fields = array_merge($this->fields, $res[0]);
			}
			$i++;
		}
	}*/
	
	public function lookupUsingEid($eid = NULL)
	{
		if($eid === NULL)
		{
			$this->Log->error('DatabaseObject->lookupUsingEid()', 'Method was called with NULL eid');
		}
		else
		{
			$this->setField('eid', $eid);
		}
		$q = "SELECT `".$this->primaryIdColumn."` FROM `".end($this->tables)->getTableName()."` WHERE `eid` = ?";
		$res = $this->DB->getSelectFirst($q, $eid);
		if($res !== FALSE)
		{
			$this->setPrimaryId($res);
			$this->lookup();
		}
		else
		{
			$this->Log->error('DatabaseObject->lookupUsingEid()', $this->primaryIdColumn.' could not be linked to eid');
		}
	}
	
	/**
	 * This function returns the $this->tables array
	 * @return array the tables this database object uses
	 */
	public function getTables()
	{
		return $this->tables;
	}
	
	/**
	 * This function returns an array of all the special fields
	 * @return DatabaseColumn[] an array of DatabaseColumn objects representing the special fields in the Entity
	 */
	public function getSpecialFields()
	{
		return $this->specialFields;
	}
}