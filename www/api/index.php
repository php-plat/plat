<?php

	header('Cache-Control: no-cache');

	exit(initAPI());


	function initAPI() {
		global $mods;

		/** Variables */
		$started 	= session_start();
		$uid 		= session_id();
		$received	= $_REQUEST;
		$expecting 	= ['class', 'method', 'type', 'data'];

		/** Include API Class and Change to System */
		include('api.php');
		chdir("../../system");

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

		/** Create an API Request Object for Input */
		$request 	= new apiRequest($class, $method, $type, $data);
		$api 		= new api();


		/** Boot Framework */
		$booted 	= include('bootstrap/bootstrap.php');

		if (!$booted) {
			throw new exception("Boot Manager Missing!");
			exit();
		}

		$plugableLibraries	= [];
		boot(new bootMode('api'), $plugableLibraries);		


		/** Create New API Core */
		$core 		= new core($api, $plugableLibraries, $mods);

		/** Add Request to Queue */
		$requestId 	= $api->addRequest($request);

		/** Process API Request */
		$results 	= $core->processRequests();
		$apiResult	= $results[$requestId];

		/** Encode Result */
		$jsonResult = json_encode($apiResult);

		return $jsonResult;
	}

?>