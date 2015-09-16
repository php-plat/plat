<?php

	class errorHandler {

		public function __construct() {
			set_error_handler(array("errorHandler", "handleError"));
		}

		public function error() {

		}

		public static function handleError($errno, $errstr, $errfile, $errline) {
			global $core;

			if (!(error_reporting() & $errno)) {
		        // This error code is not included in error_reporting
		        return;
		    }

		    switch ($errno) {
			    case E_USER_NOTICE:
			    case E_USER_WARNING:
			    case E_USER_ERROR:
			    	$msg 		= 
			    		"Fatal error on line $errline in file $errfile. \n".
			    		"[$errno] $errstr"
			    	;

			    	$core->logger->logMessage($msg);
			    	
			        exit(1);
		        break;

			    default:
			        $msg = "Unknown error type: [$errno] $errstr<br />\n";

			        $core->logger->logMessage($msg);
		        break;
		    }

		    /* Don't execute PHP internal error handler */
		    return true;
		}

	}

?>