<?php

	interface ILogger {
		public function writeEx($message, $ex);
		public function writeInfo($message);
		public function writeVerbose($message);
	}

?>