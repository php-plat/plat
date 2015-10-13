<?php

	$result 	= initAPI();
	if (!$result) {
		throw new exception("Error Posting Data");
		exit(1);
	}

	include('../www/index.html');

	function initAPI() {
		global $mods;
		global $core;

		/** Variables */
		$started 	= session_start();
		$uid 		= session_id();
		$received	= $_REQUEST;
		$expecting 	= ['class', 'method', 'type', 'data', 'token'];

		/** Include API Class and Change to System */
		include('api/api.php');
		chdir("./../system");

		/** Loop through the expected input, ignoring others to set varNames*/
		foreach ($expecting as $varName) {
			$$varName = (isset($received[$varName]))
				? $received[$varName]
				: (($varName == 'data')
					? []
					: null
				)
			;
		}


		/** Session Token */
		if (isset($_SESSION['token']) and $_SESSION['token'] and !$token) {
			$token 	= $_SESSION['token'];
		}

		/** Create an API Request Object for Input */
		$request 	= new apiRequest($class, $method, $type, $data, $token);
		$api 		= new api();


		/** Boot Framework */
		$booted 	= include('bootstrap/bootstrap.php');

		if (!$booted) {
			throw new exception("Boot Manager Missing!");
			exit();
		}

		$plugableLibraries	= [];
		boot(new bootMode('post'), $plugableLibraries);		
		$_SESSION['token'] 	= $token;


		/** Create New API Core */
		$core 		= new core($api, $plugableLibraries, $mods);

		/** Add Request to Queue */
		$requestId 	= $api->addRequest($request);

		/** Process API Request */
		$results 	= $core->processRequests();
		$apiResult	= $results[$requestId];

		return ($apiResult != false);
	}

?>