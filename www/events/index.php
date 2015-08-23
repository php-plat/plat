<?php

	header("Content-Type: text/event-stream");
	header('Cache-Control: no-cache');

	session_start();
	include('events/events.php');

	


	ob_flush();
	flush();
	exit();

?>