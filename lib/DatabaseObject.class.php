<?php

class_exists('DatabaseTable') || require(NLB_LIB_ROOT.'DatabaseTable.class.php' );
class_exists('LogService') || require(NLB_LIB_ROOT.'LogService.class.php');
class_exists('DatabaseService') || require(NLB_LIB_ROOT.'DatabaseService.class.php');
class_exists('DatabaseObjectException') || require(NLB_LIB_ROOT.'DatabaseObjectException.class.php');

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
		if($this->fields[$this->primaryIdColumn] == NULL)
		{
			$this->Log->error('DatabaseObject->lookup()', 'Method was called when primaryId not set');
		}
		foreach(array_reverse($this->tables) as $table)
		{
			$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
			$res = $this->DB->getSelectArray($q, $this->fields[$table->getPrimaryKeyColumn()]);
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

	public function save()
	{
		$insertId = FALSE;
		$lastPrimaryKeyColumn = NULL;

		$currentDateTime = date('Y-m-d H:i:s');

		if($this->fields[$this->primaryIdColumn] == NULL) // We will insert new data into the DB
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
						$columnValues[] = $this->fields[$columnName];
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

				$this->fields[$table->getPrimaryKeyColumn()] = $insertId;
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
							$columnValues[] = $this->fields[$columnName];
						}
						$columnUpdateClauses[] = "`$columnName` = ?";
					}
				}

				$columnValues[] = $this->fields[$table->getPrimaryKeyColumn()];

				$q = "UPDATE `".$table->getTableName()."` SET ".implode(', ', $columnUpdateClauses)." WHERE `".$table->getPrimaryKeyColumn()."` = ?";
				$this->DB->execUpdate($q, $columnValues);
			}
		}
		$this->lookup();
	}

	public function setPrimaryId($id)
	{
		$this->fields[$this->primaryIdColumn] = $id;
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
}
?>
