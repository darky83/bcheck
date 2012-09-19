<?php

include_once dirname(__FILE__).'/LoggerBase.php';
include_once dirname(__FILE__).'/../LoggerConfig.php';

class TextLogger extends LoggerBase {
	public function writeEx($message, $ex = null) {
		$errMessage = "Error - $message";
		if(!is_null($ex)) { $errMessage .= '\n ' .$ex->getMessage(); }
		
		$this->write($errMessage);
	}
	public function writeInfo($message) {
		$this->write("Info - $message");
	}
	public function writeVerbose($message) {
		$this->write("Verbose - $message");
	}
	
	private function write($message) {
		$fileH = fopen(LoggerConfig::$logFile, 'w') or die("Unable to open file.");
		fwrite($fileH, $this->currentDate() .":$message");
		fclose($fileH);
	}
}

?>