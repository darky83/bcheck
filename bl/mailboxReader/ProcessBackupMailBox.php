<?php

include_once dirname(__FILE__) .'/MailBox.php';

/**
 * Processes the mailbox and appends all backup attempts in the mailbox.
 * @author Robin Heemskerk
 */
class ProcessBackupMailBox {
	/**
	 * Process all new mailbox messages.
	 */
	public static function Process() {
		LoggerManager::writeInfo("Start proccssing all new messages.");
		
		//Create a new mailbox.
		$box = new MailBox(Config::$mailServer, Config::$mailPort, Config::$mailBox, Config::$dbUsername, Config::$mailPassword);
		
		//Retrieve all messages.
		$messages = $box->readMessages();
		LoggerManager::writeVerbose("Found: " .count($messages) ." new messages.");
		//Retrieve all backup validators.
		$validators = BackupValidator::getAllBackupValidators();
		LoggerManager::writeVerbose("Found: " .count($validators) ." validators.");
		
		//Loop through all messages to create a match.
		foreach($messages as $message) {
			//Loop all validators.
			foreach($validators as $validator) {
				if($validator->validate($message['message'])) {
					//We have a match.
					$backupAtt = new BackupAttempt();
					$backupAtt->message = $message['message'];
					$backupAtt->backupvalidatorId = $validator->id;
					//TODO Robin: Server ip or id als unique ID?
					//$backupAtt->server = ...
					
					//TODO Robin: save or bulk insert?
					$backupAtt->save();
					break;
				}
			}
		}
		//Success? Clean all messages.
		//$box->cleanMessages($messages);
		LoggerManager::writeInfo("Removed all messages and done.");
	}
}

?>