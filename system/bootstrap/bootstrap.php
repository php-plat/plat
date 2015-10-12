<?php

	class bootMode {

		private $type;

		protected $bootMode;

		public function __construct($bootMode = null) {
			$this->type 	= $bootMode;

			switch (strtolower($bootMode)) {

				default:
				case 1:
				case 'script':
				case 'javascript':
				case 'api':
					$this->bootMode 	= 'api';
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

	function load_config(array $configList = array()) {
		global $config;

		$folder 	= realpath("config");
		if (!$folder) return false;
		if (!isset($config) or !is_array($config)) $config = [];

		foreach ($configList as $cfg) {
			$config[$cfg] = new config($folder, $cfg);
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

	function load_plugins() {
		global $mods;
		global $plugin_dbs;

		$folder 	= realpath("plugins");
		if (!$folder) return false;		

		$list 		= scandir($folder);
		foreach ($list as $item) {
			if ($item == '.' or $item == '..') continue;
			if (!is_dir("$folder/$item")) continue;

			$manifest 	= "$folder/$item/manifest.json";
			if (!file_exists($manifest)) continue;

			$data['path']		= "$folder/$item/$item.php";
			$data['folder']		= "$folder/$item";

			$json 				= file_get_contents($manifest);
			$manifest 			= json_decode($json, true);

			$data['manifest']	= $manifest;
			$data['name']		= $manifest['name'];
			$mods[$item]		= $data;

			$libs 				= $manifest['libraries'];
			foreach ($libs as $lib) {
				$path 	= ("$folder/$item/$lib.php");
				if (!$path) continue;

				$included 		= include($path);
			}

			$databases 			= (isset($manifest['databases'])) ? $manifest['databases'] : [];
			foreach ($databases as $db) {
				if (!isset($manifest[$db])) continue;

				$plugin_dbs[$item][$db]	= $manifest[$db];
			}
		}
	}

	function load_headers() {
		global $config;

		$sys 		= (isset($config['system'])) ? $config['system'] : null;
		$headers 	= ($sys->headers) ? $sys->headers : [];

		if (!$headers) return false;
		foreach ($headers as $header) {header($header);}
		return true;
	}

	function boot(bootMode $mode, array &$plugable = array()) {
		global $mods;

		$config 		= $mode->type();
		$func 			= $config."_mode";
		$mode 			= $func();

		foreach ($mode['callFunctions'] as $function) {
			$func 		= "load_$function";
			$param 		= $mode[$function];
			$result 	= $func($param);
		}

		$plugable 		= ($mode['plugable']) ? $mode['plugable'] : [];

		foreach ($mods as $pluginName => $meta) {
			if (!isset($meta['init']) or $meta['init'] != true) continue;
			$plugable[]	= $meta['class'];
		}
			
		return true;
	}

	function api_mode() {
		$conf 	= realpath(getcwd() . "/bootstrap/api.json");

		if (!$conf) {
			throw new exception("Cannot load bootstrap config");
			return false;
		}

		$json 	= file_get_contents($conf);
		$data 	= json_decode($json, true);
		return $data;
	}

?>