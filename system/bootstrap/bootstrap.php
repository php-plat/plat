<?php

	class bootMode {

		private $type;

		protected $bootMode;

		public function __construct($bootMode = null) {
			$this->type 	= $bootMode;

			switch (strtolower($bootMode)) {

				case 1:
				case 'script':
				case 'javascript':
				case 'api':
					$this->bootMode 	= 'api';
				break;

				case 0:
				case '':
				case 'gui':
				default:
					$this->bootMode 	= 'default';
				break;

			}

		}

		public function __toString() {
			return $this->bootMode;
		}

		public function type() {
			return $this->bootMode;
		}
	}

	function load_config(array $config = array()) {
		$folder 	= realpath("config");
		if (!$folder) return false;

		foreach ($config as $cfg) {
			$path 	= realpath("$folder/$cfg.php");
			if (!$path) return false;
			/**
			@todo config class and load config into memory
			*/
		}
	}

	function load_libraries(array $libraries = array()) {
		$folder 	= realpath("library");
		if (!$folder) return false;

		foreach ($libraries as $lib) {
			$path 	= realpath("$folder/$lib.php");
			if (!$path) return false;
			include($path);
		}
	}

	function boot(bootMode $mode, array &$plugable = array()) {
		$config 		= $mode->type();
		$func 			= $config."_mode";
		$mode 			= $func();

		foreach ($mode['callFunctions'] as $function) {
			$func 		= "load_$function";
			$param 		= $mode[$function];
			$result 	= $func($param);
		}

		$plugable 		= ($mode['plugable']) ? $mode['plugable'] : [];
		return true;
	}

	function api_mode() {
		return [

			'callFunctions' 	=> [
				'config',
				'libraries'
			],

			'libraries' 		=> [
				'notes',
				'events',
				'core'
			],

			'config' 			=> [
			],

			'plugable'			=> [
				'events',
				'notes'
			]
		];
	}

	function default_mode() {
		return [

			'callFunctions' 	=> [
			],

			'libraries' 		=> [
			],

			'config' 			=> [
			],

			'plugable'			=> [
			]
		];
	}

?>