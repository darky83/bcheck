<?php

class MailBox {
	private $mPort = null;
	private $mAddress = null;
	private $mFolder = null;
	private $mUsername = null;
	private $mPassword = null;
	
	 function __construct($address, $port, $folder, $username, $password) {
	 	$this->mFolder = $folder;
	 	$this->mAddress = $address;
	 	$this->mPassword = $password;
	 	$this->mUsername = $username;
	 	$this->mPort = $port;
	 }	
	 
	 /**
	  * Read all messages from inbox and returns then as string.
	  * @return multidimensional array
	  * <br/>with: 
	  * <br/>  <i>['id'] => message id</i>
	  * <br/>  <i>['message'] => message body</i>
	  */
	 public function readMessages() {
	 	$mBox = $this->open();
	 	
	 	//Get all message id's
	 	$sorted_mbox = imap_sort($mBox, SORTDATE, 0); 
	 	$returnMessages = array();
	 	
	 	//Push all messages in an array.
	 	foreach($sorted_mbox as $msgNumber) {
	 		//get message.
	 		$message = imap_body($mBox, $msgNumber);
	 		//get header.
	 		$header = imap_header($mBox, $msgNumber);
	 		array_push($returnMessages, array('id' => $msgNumber, 'message' => $message, 'header' => $header));
	 	}
	 	
	 	//close the box.
	 	$this->close($mBox);
	 	
	 	return $returnMessages;
	 }
	 
	 /**
	  * Cleans the inbox.
	  * @param multi dim array $messages 
	  */
	 public function cleanMessages($messages) {
	 	$mBox = $this->open();
	 	//Delete each message based on the message id.
	 	foreach($messages as $message) {
	 		imap_delete($mBox, $message['id']);
	 		LoggerManager::writeVerbose("Removed message id {$message['id']}");
	 	}
	 	
	 	$this->close($mBox);
	 }
	 
	 /**
	  * Prints all mailbox settings as html text (date, driver, unread and size). 
	  */
	 public function printMailBoxSettings() {
	 	//Try to open the inbox.
	 	if($mBox = $this->open()) {
	 		LoggerManager::writeVerbose("Connected to the mailbox.");
	 		
	 		//Get inbox info.
	 		//$checkInfo = imap_mailboxmsginfo($mBox);
	 		//write verbose info over mailbox.
	 		LoggerManager::writeVerbose("MailBox: $checkInfo->Mailbox Unread: $checkInfo->Unread Size: $checkInfo->Size");
	 		
	 		//Close box
	 		$this->close($mBox);
	 	}
	 	else {
	 		LoggerManager::writeEx("Cannot connect to the mailbox.");
	 		exit ("Cannot connect to the mailbox: " .imap_last_error());
	 	}
	 }
	 
	 /**
	  * Opens the mailbox and returns a new inbox object.
	  */ 
	 private function open() {
	 	return imap_open('{' ."$this->mAddress:$this->mPort" .'}' .$this->mFolder, $this->mUsername, $this->mPassword);
	 }
	 
	 /**
	  * Closes the given mailbox..
	  * @param unknown_type $inbox
	  */
	 private function close($inbox) {
	 	imap_close($inbox);
	 }
}

?>