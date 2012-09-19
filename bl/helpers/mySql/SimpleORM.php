<?php

include_once dirname(__FILE__) .'/MySqlConnector.php';

/**
 * Creates a few basic DAL features and creates the ability to map array's to objects.
 * @author Robin Heemskerk
 */
class SimpleORM {
	/**
	 * <h2>Constructs a new SimpleORM based on a class object.</h2>
	 * <i>The class object properties require the same names as the database properties.</i> 
	 * @param string $class The classname used for the instance.
	 * @param string $server The sql server to connect to.
	 * @param string $user Server username.
	 * @param string $password Server password.
	 * @param string $database The database to connect to.
	 */
	function __construct($class, $tableName, $primKeyName, $server, $user, $password, $database) {
		$this->mTableName = $tableName;
		$this->mPrimKeyName = $primKeyName;
		//Create a new reflection class.
		$this->mClass = new ReflectionClass($class);
		//Get all public properties.
		$props = $this->mClass->getProperties(256);
		$this->mArrProperties = array();
		//Append all property names.
		foreach($props as $prop) {
			array_push($this->mArrProperties, $prop->name);
		}
		//Create a new sql connector.
		$this->mSqlConnector = new MySqlConnector($server, $user, $password, $database);
	}	
	
	private $mClass;
	private $mArrProperties;
	private $mTableName;
	private $mPrimKeyName;
	public $mSqlConnector;
	
	/**
	 * inserts the given (reference) instance in the database.
	 * @param mixed $instance [REFERENCE] Instance to add (must sync the given class)
	 */
	public function Insert(&$instance) {
		//query: INSERT INTO [table] (..., ...) VALUES (..., ...)
		$sql = "INSERT INTO $this->mTableName (" .implode(", ", $this->mArrProperties). ") VALUES (";
		//Append all values.
		foreach($this->mArrProperties as $value) {
			$sql .= "'" .$instance->{$value} ."', ";
		}
		
		//Remove the last ', '
		$sql = substr_replace($sql, "", strlen($sql) -2, 2);
		//finish with an )
		$sql .= ")";
		
		//Insert and set the new id.
		$instance->{$this->mPrimKeyName} = $this->mSqlConnector->queryLastAutoIncId($sql);
	}
	
	/**
	 * Updates the given (reference) instance in the database.
	 * @param mixed $instance [REFERENCE] Instance to update (must sync the given class). Requires the instance to exist.
	 */
	public function Update(&$instance) {
		//query: UPDATE [table] SET ... = ... WHERE [primKey] = ...
		$sql = "UPDATE $this->mTableName SET ";
		
		foreach($this->mArrProperties as $value) {
			$sql .= "$value = '" .$instance->{$value} ."', ";
		}
		
		//remove last ', '
		$sql = substr_replace($sql, "", strlen($sql) -2, 2);
		$sql .= "WHERE $this->mPrimKeyName = " .$instance->{$this->mPrimKeyName};		
	}
	
	/**
	 * Saves the instance in database. Determines if the instance already exists.
	 * If this is the case it will perform an update based on the prim key.
	 * @param mixed $instance [REFERENCE]Instance to save (insert/update).
	 */
	public function save(&$instance) {
		if($this->exists($instance)) {
			$this->update($instance);
		}
		else {
			$this->insert($instance);
		}
		
		
	}
	
	/**
	 * Deletes the given instance.
	 * @param mixed $instance [REFERENCE]Instance to delete
	 */
	public function delete(&$instance) {
		//query DELETE FROM [table] WHERE [primKey] = ...
		$sql = "DELETE FROM $this->mTableName WHERE $this->mPrimKeyName = " .$instance->{$this->mPrimKeyName};
		$this->mSqlConnector->queryNonResult($sql);
	}
	
	/**
	 * Checks if an instance exists based on the primary ID.
	 * @param mixed $instance [REFERENCE]Instance to check.
	 */
	public function exists(&$instance) {
		return $this->GetSingle($this->mPrimKeyName, $instance->{$this->mPrimKeyName}) != null;
	}
	
	/**
	 * Get an single item based on a field and value.
	 * @param unknown_type $field Field to match.
	 * @param unknown_type $value Value to match.
	 * @return Returns null (if not found) or the first item found.
	 */
	public function getSingle($field, $value) {
		$res = $this->mSqlConnector->queryWithResult("SELECT * FROM $this->mTableName WHERE $field = $value LIMIT 1");
		if(count($res) == 0) {
			return null;
		}
		else {
			return $this->map($res[0]);
		}
	}
	
	/**
	 * Fill an instance (overrides instance properties). 
	 * @param mixed $instance [REFERENCE] Instance to fill.
	 */
	public function fill(&$instance) {
		$new = $this->getSingle($this->mPrimKeyName, $instance->{$this->mPrimKeyName});
		foreach($this->mArrProperties as $prop) {
			$instance->{$prop} = $new->{$prop};
		}	
	}
	
	/**
	 * Get all based on a WHERE clause (without WHERE clause). (creates an assoc array where the id is the key).
	 * @param string $condition WHERE clause
	 */
	public function getAll($condition) {
		$res = $this->mSqlConnector->queryWithResult("SELECT * FROM $this->mTableName $condition");
		$returnArr = array();

		if(count($res) > 0) {
			foreach($res as $row) {
				array_push($returnArr, $this->map($row));
			}
		}
		return $returnArr;
	}
	
	/**
	 * map a given row to the initiated class.
	 * @param array $row Row to map.
	 */
	private function map($row) {
		//Create new instance.
		$newInstance = $this->mClass->newInstance();
		
		//Fill it.
		foreach($this->mArrProperties as $prop) {
			$newInstance->{$prop} = $row[$prop];
		}
		
		return $newInstance;
	}
	
	public function BulkInsert(&$instances) {
		die('Not implemented.');
	}
}

?>