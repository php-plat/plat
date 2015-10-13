<?php

	class mailer {

		function __construct() {}

		public function send($from, $to, $subject, $body) {
			global $config;
			global $core;

			require('Mail.php');
			$sys 	= $config['system'];
			$mail 	= $sys->mail;
			$smtp 	= Mail::factory('smtp', $mail); 

			$headers['From']    		= $from;
			$headers['To']      		= $to;
			$headers['Subject'] 		= $subject;
			$headers['MIME-Version'] 	= 1;
    		$headers['Content-type']  	= 'text/html;charset=iso-8859-1';

			$mail = $smtp->send($to, $headers, $body); 
			
			if (PEAR::isError($mail)) { 
				$err = $mail->getMessage();
				$core->logger->log("-Mail-\n$err\n");

				return false;
			}

			return true;
		}
	}

?>