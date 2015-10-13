<?php

	abstract class plugin {

		public $name;
		public $errors;

		protected $meta;
		protected $databases;
		protected $connections;
		protected $config;

		private $default_connection;


		/** Magic Functions */
			public function __construct($name = null) {
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


		/** Private Functions */
			private function loadConfig() {
				$folder 	= (isset($this->meta['folder'])) ? $this->meta['folder'] : null;
				$cfgs 		= (isset($this->meta['manifest']['config'])) ? $this->meta['manifest']['config'] : [];

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


		/** Abstract */
			protected function table($tableName, $connectionName = null) {

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

			protected function query($sql) {
				$table 	= $this->table("");

				if (!$sql or !$table) return false;
				return $table->query($sql);
			}

			protected function clientEvent($eventName, $eventData) {
				global $core;
				return $core->events->add($eventName, $eventData);
			}

			protected function clientNotification($title, $message) {
				global $core;
				return $core->notes->add($title, $message);
			}


		/** UX */
			public function ui($page = null) {
				return new ui($this, $this->meta, $page);
			}

	}

	final class ui {

		private $plugin;
		private $meta;
		private $folder;
		private $path;

		public $page;
		public $html;

		public function __construct(plugin $plugin, array $meta, $page = null) {
			$this->plugin 	= $plugin;
			$this->meta 	= $meta;
			$this->page 	= $page;

			$this->init();
		}

		private function init() {
			$this->folder 	= "{$this->meta['folder']}/ux";
			if ($this->page) $this->path = realpath("{$this->folder}/{$this->page}.php");
			if ($this->path) $this->html = file_get_contents($this->path);
		}

		public function __invoke() {
			return $this->html();
		}

		public function html() {
			return $this->html;
		}

		public static function dialog($dialogPage, array $param, $render = false) {

			$file 		= realpath("dialog/$dialogPage.php");
			if (!$file) return false;

			ob_start();
				include($file);
				$html 	= ob_get_clean();

			//$html 		= file_get_contents($file);
			if ($render) print $html;

			return $html;
		}

	}
	
?>