<?php

include_once 'includes/Config.php';
include_once dirname(__FILE__) .'/helpers/mySql/SimpleORM.php';

/**
 * Backup validator class. Object containing the regex to judge a message.
 * @author Robin Heemskerk
 *
 */
class BackupValidator {
	public $id;
	public $backupType;
	public $successRegex;
	public $failureRegex;
	
	private static $mOrm;
	private static function getOrm() {
		if(self::$mOrm == null) {
			self::$mOrm = new SimpleORM(__CLASS__, "backupvalidator", "id", Config::$dbServer, Config::$dbUsername, Config::$dbPassword, Config::$dbDatabase);
		}	
		
		return self::$mOrm;
	}
	
	/**
	 * <h2>Creates a new BackupValidator.</h2> 
	 * <i>When id is filled it will retrieve the matching data from database. 
	 * @param int $id [OPTIONAL]The id to lookup
	 */
	function __construct($id = null) {
	
		if($id != null) {
			//Set the id for the validator.
			$this->id = $id;
			//Fill all data from database.
			self::getOrm()->fill($this);
		}
		else {
			$this->id = 0;
		}
	}
	
	public function save() {
		self::getOrm()->save($this);
	}
	
	public function delete() {
		self::getOrm()->delete($this);
	}
	
	/**
	 * validate an text on success and failre regex.
	 * Returns true (if success regex is found) or false (if failure regex is found).
	 * Will return <i>null</i> if no match could be made.
	 * @param string $text Text to match.
	 */
	public function validate($text) {
		if(count(preg_match($this->successRegex, $text)) <= 0) {
			return true;
		}
		elseif(count(preg_match($this->failureRegex, $text)) > 0) {
			return false;
		}
		else {
			return null;
		}
	}
	
	/**
	 * Get all backup validators.
	 */
	public static function getAllBackupValidators() {
		//get all without condition.
		return self::getOrm()->getAll("");
	}
}
?>