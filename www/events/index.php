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
		$parts 					= explode(', ', $load, 3);

		foreach ($parts as $index => $part) {
			$parts[$index] 	= (int) (($part * pi()) * 360);
		}

		//Keep Alive Ping
		$pingData 	= [
			"id"				=> uniqid("kuhl-"),
			"server-time"		=> date($serverTimeFormat),
			"load"				=> $parts
		];
		$events->add("ping", $pingData);

		//Send Pending Events for Session
		$events->sendEvents();
	}

	init_events();

	exit();

?>