<?php

class LoggerConfig {
		//Text logging
		public static $logFile = "logs/logging.txt";
		//Accepted: text;screen
		public static $logging = "screen;";
		//1 is Error only. 2 is info. 3 is verbose and lower.
		public static $loggingLevel = 3;
}

?>