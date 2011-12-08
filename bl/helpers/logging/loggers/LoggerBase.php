<?php

	abstract class LoggerBase {
		abstract function writeEx($message, $ex = null);
		abstract function writeInfo($message);
		abstract function writeVerbose($message);
		
		protected function currentDate() {
			return date('Y-m-d H:i:s');
		}
	}

?>