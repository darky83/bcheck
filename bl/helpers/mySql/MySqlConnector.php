<?php

/**
 * 
 * Simple MySql class wrapper.
 * @author Robin Heemskerk
 *
 */
class MySqlConnector {
	public $mServer;
	public $mUsername;
	public $mPassword; 
	public $mDatabase;
	
	/**
	 * <h2>Creates a new MySql connector based on the given parameters.</h2>
	 * <i>These will be used for each database query.</i> 
	 * @param string $server MySql server to connect to.
	 * @param string $username Username for MySql server.
	 * @param string $password Password for MySql server.
	 * @param string $database Database to connect to.
	 */
	function __construct($server, $username, $password, $database) {
		$this->mServer = $server;
		$this->mUsername = $username;
		$this->mPassword = $password;
		$this->mDatabase = $database;
	}
	
	/**
	 * Open a new mysql connection.
	 * @return a new openen connection. Will die if opening an connection failed.
	 */
	private function open() {
		if($conn = mysql_connect($this->mServer, $this->mUsername, $this->mPassword)) {
			//Select the correct database on success.
			mysql_select_db($this->mDatabase, $conn);
			return $conn;
		}
		else {
			LoggerManager::writeEx("Could not open a mysql connection: ". mysql_error());
			die ('Could not close mysql connection..');
		}
	}
	
	/**
	 * Close the given mysql connection.
	 * @param resource $conn Connection to close.
	 */
	private function close($conn) {
		if(mysql_close($conn) == false) {
			LoggerManager::writeEx('Could not close the mysql connection: '. mysql_error());
			die('could open mysql connection.');
		}
	}
	
	/**
	 * Execute a query without expecting result.
	 * @param string $query Query to execute.
	 */
	public function queryNonResult($query) {
		$conn = $this->open();
		//Excecute query.
		if(mysql_query($query) == false) {
			LoggerManager::writeEx("Failed to execute non result mysql query: $query. Error: ". mysql_error());
			$this->close($conn);
			die('Failed to execute non result mysql query.');
		}
		$this->close($conn);
	}
	
	/**
	 * Execute a query returning the last auto inc id.
	 * @param string $query Quer to execute.
	 * @return Returns the last auto inc id.
	 */
	public function queryLastAutoIncId($query) {
		$conn = $this->open();
		//Excecute query.
		if(mysql_query($query) == false) {
			LoggerManager::writeEx("Failed to execute non result (returning last autoInc id) mysql query: $query. Error: ". mysql_error());
			$this->close($conn);
			die('Failed to execute non result mysql query.');
		}
		$returnId = mysql_insert_id(); 	//Get the last auto inc id.
		$this->close($conn); 			//Close the connection.
		return $returnId;
	}
	
	/**
	 * Execute a query expecting result.
	 * @param string $query Query to excecute.
	 */
	public function queryWithResult($query) {
		$conn = $this->open();

		if($res = mysql_query($query)) {
			$returnArr = array();
			while($fetched = mysql_fetch_assoc($res)) {
				array_push($returnArr, $fetched);
			}
			$this->close($conn);
			return $returnArr;
		}
		else {
			LoggerManager::writeEx("Failed to execute result mysql query: $query. Error: ". mysql_error());
			$this->close($conn);
			die('Failed to execute result mysql query.');
		}
	}
}