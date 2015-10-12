<?php

	header("Content-Type: text/event-stream");
	header('Cache-Control: no-cache');

	global $eventTime;
	$eventTime 					= 3;
	
	session_start();
	include('events.php');

	function init_events() {
		$serverTimeFormat		= "\I\\t \i\s g:i a T \o\\n l, F jS \o\\f Y";
		$uptime 				= `uptime`;
		$events 				= new events();

		$parts 					= explode("average: ", $uptime, 2);
		$load 					= $parts[count($parts)-1];
		
		//Keep Alive Ping
		$pingData 	= [
			"id"				=> uniqid("plat-"),
			"server-time"		=> date($serverTimeFormat),
			"server-load"		=> $load,
			"server"			=> $_SERVER
		];

		$events->add("ping", $pingData);

		//Send Pending Events for Session
		$events->sendEvents();
	}

	init_events();

	exit();

?>