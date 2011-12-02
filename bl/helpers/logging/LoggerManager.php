<?php

include 'LoggerConfig.php';
include 'loggers/ILogger.php';
include 'loggers/TextLogger.php';

class LoggerManager {
	
	private static $loggers = null;
	private static function getLoggers() {
		//Determine if loggers is null, else create loggers for this session.
		//All implement the ILogger interface.
		if($loggers == null) {		
			foreach(split(";", LoggerConfig::$logging) as $value) {
				//Possible logger types:
				//Text
				//TODO Robin: database logger.
				//text logger is the only one @ this moment.
				if(strtolower($value == "text")) {
					array_push($returnLoggers, new TextLogger());
				}
			}
		}
		return $loggers;
	}
	
	public static function writeEx($message, $ex) {
		//Always write this errors.
		foreach(self::getLoggers() as $logger) {
			$logger->writeEx($message, $ex);
		}
	}
	
	public static function writeInfo($message) {
		if(LoggerConfig::$loggingLevel >= 2) {
			foreach(self::getLoggers() as $logger) {
				$logger->writeInfo($message);
			}
		}
	}
	
	public static function writeVerbose($message) {
		if(LoggerConfig::$loggingLevel >= 3) {
			foreach(self::getLoggers() as $logger) {
				$logger->writeVerbose($message);
			}
		}
	}
}

?>