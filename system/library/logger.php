<?php

	class logger {

		public function __construct() {

		}

		public function logMessage($string) {
			return file_put_contents('log/messages.log', $string . "\n", FILE_APPEND);
		}
		
	}

?>