<?php

class ScreenLogger extends LoggerBase {
	public function writeEx($message, $ex = null) {
		$errMessage = "Error - $message";
		if(!is_null($ex)) { $errMessage .= "<br/>" .$ex->getMessage();	}
		
		$this->write($errMessage);
	}
	public function writeInfo($message) {
		$this->write("Info - $message");
	}
	public function writeVerbose($message) {
		$this->write("Verbose - $message");
	}
	
	private function write($message) {
		echo "<br/>" .$this->currentDate(). ": $message";
	}
}


?>