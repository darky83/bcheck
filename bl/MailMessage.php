<?php

class MailMessage {
	public $id;
	public $message;
	public $subject;
	
	private static $mOrm;
	private static function getOrm() {
		if(self::$mOrm == null) {
			self::$mOrm = new SimpleORM(__CLASS__, "mailmessage", "id", Config::$dbServer, Config::$dbUsername, Config::$dbPassword, Config::$dbDatabase);
		}
	
		return self::$mOrm;
	}
	
	/**
	* <h2>Creates a new MailMessage.</h2>
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
}

?>