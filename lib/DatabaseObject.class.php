<?php
	require_once(NLB_LIB_ROOT.'DatabaseTable.class.php' );
	require_once(NLB_LIB_ROOT.'LogService.class.php');
	require_once(NLB_LIB_ROOT.'DatabaseService.class.php');
	
	class DatabaseObject
	{
		protected $tables = array();
		protected $fields = array();
		protected $primaryId = '';
		protected $Log;
		protected $DB;
		
		public function __construct($primaryid = NULL)
		{
			$this->Log = LogService::getInstance();
			$this->DB = DatabaseService::getInstance();
			if($primaryid !== NULL)
			{
				$this->primaryId = $primaryid;
			}	
		}
		
		public function lookup()
		{
			if($this->primaryId == NULL)
			{
				$this->Log->error('DatabaseObject->lookup()', 'Method was called when primaryId not set');
			}
			foreach($this->tables as $table)
			{
				$q = "SELECT * FROM `".$table->getTableName()."` WHERE `".$table->getPrimaryKeyColumn()."` = ?";
				$res = $this->DB->getSelectArray($q, $this->primaryId);
				if(count($res) > 0)
				{
					$this->fields = array_merge($this->fields, $res[0]);
				}
			}
		}
		
		public function save()
		{
			$insertId = FALSE;
			$lastPrimaryKeyColumn = NULL;
			if($this->primaryId == NULL)
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
							$columnValues[] = date('Y-m-d H:i:s');
						}
						else
						{
							$columnValues[] = $this->fields[$columnName];
						}
					}
					
					if($insertId !== FALSE)
					{
						$columnNames[] = "`$lastPrimaryKeyColumn`";
						$columnValues[] = $insertId;
					}
					
					$q = "INSERT INTO `".$table->getTableName()."` (".implode(', ', $columnNames).") VALUES (".trim(str_repeat('?,', count($columnValues)), ',').")";
					$insertId = $this->DB->execUpdate($q, $columnValues);
					
					$lastPrimaryKeyColumn = $table->getPrimaryKeyColumn();
				}
			}
			else
			{
				
			}
		}
		
		public function setPrimaryId($id)
		{
			$this->primaryId = $id;
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
