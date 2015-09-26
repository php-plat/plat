<?php

	class database {

		protected $hostOptions	= null;
		public $connection	= null;

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

		public  $name;
		protected $database;

		public $lastResult;

		public function __construct($tableName, database $connection) {
			$this->name 	= $tableName;
			$this->database = $connection;
		}

		public function __invoke($input = null) {
			if (is_numeric($input)) {
				return $this->record($input);
			} elseif (is_array($input)) {
				return $this->commit($input);
			} elseif (is_string($input)) {
				return $this->query($input);
			} else {
				return $this;
			}
		}

		public function query($sql) {
			$connection 	= $this->database->connection;
			$result 		= $connection->query($sql);
			$data 			= [];

			if (!$result) {
				$this->errors[] 	= $connection->error;
				return false;
			} else {
				$this->lastResult	= $result;
			}

			while ($row = $result->fetch_assoc()) {
				$data[]	= $row;
			}

			return $data;
		}

		public function record($id) {
			$records 	= $this->query("SELECT * FROM `{$this->name}` WHERE `id` = '$id';");
			return (isset($records[0])) ? $records[0] : false;
		}

		public function insert(array $record = array(), array $buffer = array()) {

		}

		public function update($id, array $data = array(), array $buffer = array()) {

		}

		public function delete($id, array $buffer = array()) {

		}

		public function commit(array $data = array()) {

		}

	}

?>