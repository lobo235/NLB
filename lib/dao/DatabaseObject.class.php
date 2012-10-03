<?php

class_exists('DatabaseTable') || require(NLB_LIB_ROOT.'dao/DatabaseTable.class.php' );
class_exists('LogService') || require(NLB_LIB_ROOT.'services/LogService.class.php');
class_exists('DatabaseService') || require(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('DatabaseObjectException') || require(NLB_LIB_ROOT.'exceptions/DatabaseObjectException.class.php');

class DatabaseObject
{
	protected $tables = array();
	protected $fields = array();
	protected $primaryIdColumn = NULL;
	protected $Log;
	protected $DB;

	public function __construct()
	{
		$this->Log = LogService::getInstance();
		$this->DB = DatabaseService::getInstance();
	}

	public function lookup()
	{
		if($this->getField($this->primaryIdColumn) == NULL)
		{
			$this->Log->error('DatabaseObject->lookup()', 'Method was called when primaryId not set');
		}
		foreach(array_reverse($this->tables) as $table)
		{
			$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
			$res = $this->DB->getSelectArray($q, $this->getField($table->getPrimaryKeyColumn()));
			if($res && count($res) > 0)
			{
				if(isset($res[0][$this->primaryIdColumn]))
				{
					unset($res[0][$this->primaryIdColumn]);
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
		$insertId = FALSE;
		$lastPrimaryKeyColumn = NULL;

		$currentDateTime = date('Y-m-d H:i:s');

		if($this->getField($this->primaryIdColumn) == NULL) // We will insert new data into the DB
		{
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
		else // We have a primary key so update the existing data in the DB
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
		$this->lookup();
	}
	
	/**
	 * Deletes the current DatabaseObject from the Database
	 * @throws DatabaseObjectException 
	 */
	public function delete()
	{
		if($this->getField($this->primaryIdColumn) != NULL) // We can only delete an object that has a primaryId set
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
		if($id != NULL)
			$this->lookup();
	}

	protected function setPrimaryKeyColumn($name)
	{
		$this->primaryKeyColumn = $name;
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
	
	public function lookupUsingEid($eid = NULL)
	{
		if($eid == NULL)
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
	}
}
?>
