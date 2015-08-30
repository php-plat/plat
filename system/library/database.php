<?php

	class database {

		protected $hostOptions	= null;
		protected $connection	= null;

		public $ready 			= false;
		public $errors 			= [];

		public function __construct(databaseOptions $host = null) {
			if ($host) $this($host);
		}

		public function __invoke(databaseOptions $host = null) {
			if ($host) {

				$this->hostOptions 	= $host;
				$connection 		= $this->connect();

				if ($connection->connect_errno) {
					$this->errors[]	= $connection->connect_error;
					$this->ready 	= false;
					return $connection->connect_error;
				} else {
					$this->ready 	= true;
					return $this->connection;
				}
			} else {
				return $this->connection;
			}
		}

		public function connect() {
			$this->connection 	= new mysqli(
				$this->hostOptions->host,
				$this->hostOptions->username,
				$this->hostOptions->password,
				$this->hostOptions->database,
				$this->hostOptions->port
			);

			return $this->connection;
		}

		public function table($name) {
			return new table($name, $this);
		}
	}

	class databaseOptions {

		public $host;
		public $port;
		public $username;
		public $password;
		public $database;

		function __construct($host, $port = 3306, $username = 'root', $password = null, $database = 'test') {
			$this->host 		= $host;
			$this->port 		= $port;
			$this->username 	= $username;
			$this->password 	= $password;
			$this->database 	= $database;
		}
	}

	class table {

		protected $name;
		protected $database;

		public function __construct($tableName, database $connection) {
			$this->name 	= $tableName;
			$this->database = $connection;
		}

		public function query() {

		}

	}

?>