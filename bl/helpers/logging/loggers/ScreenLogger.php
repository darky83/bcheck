<?php

class ScreenLogger extends LoggerBase {
	public function writeEx($message, $ex = null) {
		$errMessage = "<b><u>Error</u></b> - $message";
		if(!is_null($ex)) { $errMessage .= "<br/>" .$ex->getMessage();	}
		
		$this->write($errMessage);
	}
	public function writeInfo($message) {
		$this->write("<b><u>Info</u></b> - $message");
	}
	public function writeVerbose($message) {
		$this->write("<b><u>Verbose</u></b> - $message");
	}
	
	private function write($message) {
		echo "<br/><i><u>" .$this->currentDate(). "</u></i>: $message";
	}
}


?>