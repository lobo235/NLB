<?php
	require_once(NLB_LIB_ROOT.'DatabaseTable.class.php' );
	require_once(NLB_LIB_ROOT.'LogService.class.php');
	require_once(NLB_LIB_ROOT.'DatabaseService.class.php');
	
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
				if(count($res) > 0)
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
				foreach($this->tables as $table)
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
					$insertId = $this->DB->execUpdate($q, $columnValues);
					
					$lastPrimaryKeyColumn = $table->getPrimaryKeyColumn();
					
					$this->fields[$table->getPrimaryKeyColumn()] = $insertId;
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
			return $this->fields[$name];
		}
		
		public function setField($name, $value)
		{
			$this->fields[$name] = $value;
		}
	}
?>
