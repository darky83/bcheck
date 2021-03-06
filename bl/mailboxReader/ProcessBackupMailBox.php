<?php

include_once dirname(__FILE__) .'/MailBox.php';

/**
 * Processes the mailbox and appends all backup attempts in the mailbox.
 * @author Robin Heemskerk
 */
class ProcessBackupMailBox {
	/**
	 * <h2>Search an server matching the given serverId</h2>
	 * @param int $serverId The server id.
	 * @param array $servers Server list to search in.
	 */
	private static function &getServer($serverId, &$servers) {
		foreach($servers as $server) {
			if($server->id == $serverId)
				return $server;
		}
		return null;
	}
	
	/**
	 * <h2>Search an validator matching the given validatorId</h2>
	 * @param int $serverId The validator id.
	 * @param array $validators Validator list to search in.
	 * @return Returns a found array of references to the corresponding validators.
	 */
	private static function &getValidators($serverId, &$validators) {
		$found = array();
		
		foreach($validators as $validator) {
			if($validator->backupServerId == $serverId)
				array_push($found, $validator);
		}
		return $found;
	}
	
	/**
	 * Process all new backup mails located in the mailbox.
	 */
	public static function process() {
		LoggerManager::writeInfo("Start proccessing all new messages.");
		
		//Create a new mailbox.
		$box = new MailBox(Config::$mailServer, Config::$mailPort, Config::$mailBox, Config::$mailUser, Config::$mailPassword);
		
		//Retrieve all messages.
		$messages = $box->readMessages();
		LoggerManager::writeVerbose("Found: " .count($messages) ." new messages.");
		//Retrieve all backup validators.
		$validators = BackupValidator::getAllBackupValidators();
		LoggerManager::writeVerbose("Found: " .count($validators) ." validators.");
		$servers = BackupServer::getAll();
		LoggerManager::writeVerbose("Found: " .count($servers) ." servers.");
		
		//Loop through all messages to create a match.
		foreach($messages as $message) {
			LoggerManager::writeVerbose("starting on " .$message['header']->subject);

			//Search an server
			//--------------------------------------------------------------
			$foundServer = null;
			//Search for an server
			foreach($servers as &$server) {
				if($server->validate($message['header']->subject)) {
					//Found!
					$foundServer = &$server;
					break;
				}
				//Next attempt on the message.
				else if($server->validate($message['message'])) {
					//Second attempt results a found!
					$foundServer = &$server;
					break;
				}
			}
			
			if($foundServer != null) { 
				LoggerManager::writeVerbose("Server found: $foundServer->serverName");
				//Get the validator (returns an reference from validators array).
				$foundValidators = self::getValidators($server->id, $validators);
				if(count($foundValidators) > 0)
					LoggerManager::writeVerbose("Validator found: $foundValidators[0]->id");
				else
					LoggerManager::writeVerbose("No validators found.");
				
				$result = null;
				foreach($foundValidators as $foundValidator) {
					//Try to make an result
					$result = $foundValidator->validate($message['header']->subject);
					//Result still null, try again with the body.
					if($result == null)
						$result = $valid->validate($message['body']);

					//Break if result found.
					if($result != null) {
						Loggermanager::writeVerbose("Found an matching validator. Result: $result");
						break;
					}
				}
				
				if($result == null)
					LoggerManager::writeVerbose("No check result found...");
				else 
					LoggerManager::writeVerbose("Result found: $result");
				
				//Create a new mailmessage and save it.
				$message = new MailMessage();
				echo $message['message'];
				$message->message = $message['message'];
				$message->subject = $message['header']->subject;
				$message->save(); //Will set the message id.
					
				//Create a new backup attempt.
				$backupAtt = new BackupAttempt();
				$backupAtt->mailMessageId = $message->id;
				$backupAtt->backupvalidatorId = $validator->id;
				$backupAtt->backupDate = $message['header']->date;
				$backupAtt->result = $result;
				$backupAtt->serverId = $foundServer->id;
					
				//Save the backup attempt.
				$backupAtt->save();	//TODO Robin: save or bulk insert?
			}
			else
				LoggerManager::writeVerbose("No server found...");
		}
		//Success? Clean all messages.
		//$box->cleanMessages($messages);
		LoggerManager::writeInfo("Removed all messages and done.");
	}
}

?>