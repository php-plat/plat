<?php

	abstract class plugin {

		public $name;
		public $errors;

		protected $meta;
		protected $databases;
		protected $connections;
		protected $config;

		private $default_connection;

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

		public function __invoke($key = null) {
			if ($key) return $this->$key;
			return $this->config;
		}

		public function __toString() {
			return json_encode($this);
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

				$host		= new databaseOptions($host, $port, $username, $password, $schema);
				$database 	= new database($host);

				if (!$database->ready) {
					$this->errors[]	= $database->errors;
					continue;
				}

				if (!$this->default_connection) $this->default_connection = $database;

				$this->connections[$dbName]	= $database;
			}
		}

		public function table($tableName, $connectionName = null) {

			$connection 		= ($connectionName and isset($this->connections[$connectionName]))
				? $this->connections[$connectionName]
				: (($this->default_connection)
					? $this->default_connection
					: null
				)
			;

			if (!$connection) return false;
			return $connection->table($tableName);
		}

		public function query($sql) {
			$table 	= $this->table("");

			if (!$sql or !$table) return false;
			return $table->query($sql);
		}

		public function clientEvent($eventName, $eventData) {
			global $core;
			return $core->events->add($eventName, $eventData);
		}

		public function clientNotification($title, $message) {
			global $core;
			return $core->notes->add($title, $message);
		}

	}
	
?>