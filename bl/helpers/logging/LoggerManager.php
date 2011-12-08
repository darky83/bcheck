<?php

include_once dirname(__FILE__).'/LoggerConfig.php';
include_once dirname(__FILE__).'/loggers/LoggerBase.php';
include_once dirname(__FILE__).'/loggers/TextLogger.php';
include_once dirname(__FILE__).'/loggers/ScreenLogger.php';

class LoggerManager {
	
	private static $loggers = null;
	private static function getLoggers() {
		//Determine if loggers is null, else create loggers for this session.
		//All implement the ILogger interface.
		if(self::$loggers == null) {		
			//Create an new array and fill.
			self::$loggers = array();
			
			foreach(explode(';', LoggerConfig::$logging) as $value) {
				//Possible logger types:
				//Text
				// TODO Robin: database logger maken.
				//Create a textlogger if provided in config.
				if(strtolower($value) == "text") {
					array_push($loggers, new TextLogger());
				}
				//Create a screenlogger if provided in config.
				elseif(strtolower($value) == "screen") {
					array_push(self::$loggers, new ScreenLogger());
				}
			}
		}
		return self::$loggers;
	}
	
	/**
	 * Write an error to all registered Loggers.
	 * @param string $message Message to write.
	 * @param Exception $ex (optional) Exeption to log.
	 */
	public static function writeEx($message, $ex = null) {
		//Always write this errors.
		foreach(self::getLoggers() as $logger) {
			$logger->writeEx($message, $ex);
		}
	}
	
	/**
	 * Write an info error (level 2) to all registered loggers.
	 * @param string $message Message to write.
	 */
	public static function writeInfo($message) {
		if(LoggerConfig::$loggingLevel >= 2) {
			foreach(self::getLoggers() as $logger) {
				$logger->writeInfo($message);
			}
		}
	}
	
	/**
	 * Write an verbose error (level 1) to all registered loggers.
	 * @param string $message Message to write.
	 */
	public static function writeVerbose($message) {
		if(LoggerConfig::$loggingLevel >= 3) {
			foreach(self::getLoggers() as $logger) {
				$logger->writeVerbose($message);
			}
		}
	}
}

?>