<?php

	class plugin {

		public $name;
		public $errors;

		protected $meta;
		protected $databases;
		protected $connections;
		protected $config;

		public function __construct($name) {
			global $mods;
			global $plugin_dbs;

			$this->name 		= $name;
			$this->connections 	= [];
			$this->meta 		= (isset($mods[$name])) 		? $mods[$name] 			: [];
			$this->databases 	= (isset($plugin_dbs[$name]))	? $plugin_dbs[$name]	: [];

			$this->loadConfig();
			$this->loadDbs();
		}

		public function __get($key) {
			return (isset($this->config[$key]))
				? $this->config[$key]
				: null
			;
		}
		
		private function loadConfig() {
			$folder 	= $this->meta['folder'];
			$cfgs 		= $this->meta['manifest']['config'];

			foreach ($cfgs as $cfg) {
				$path 	= "$folder/$cfg.json";
				if (!realpath($path)) continue;

				$json 	= file_get_contents($path);
				if (!$json) continue;

				$conf 	= json_decode($json, true);
				if (!$conf) continue;

				foreach ($conf as $key => $set) {
					$this->config[$key]	= $set;
				}
			}
		}

		private function loadDbs() {
			$dbs 	= $this->databases;

			foreach ($dbs as $dbName => $db) {
				foreach ($db as $var => $val) {$$var = $val;}

				$conn	= new mysqli($host, $username, $password, $schema, $port);

				if ($conn->connect_errno) {
					$this->errors[]	= $conn->connect_error;
					continue;
				}

				$this->connections[$dbName]	= $conn;
			}
		}

	}
	
?>