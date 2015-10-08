<?php

	class test extends plugin {

		public function __construct() {
			parent::__construct('test');
		}

		public function testQuery($query) {
			return $this->query($query);
		}

		public function testNote($noteTitle, $noteMessage) {
			return $this->clientNotification($noteTitle, $noteMessage);
		}

		public function testEvent($eventName, array $data = array()) {
			return $this->clientEvent($eventName, $data);
		}

		public function testTable($tableName, $connectionName = null) {
			return $this->table($tableName, $connectioName);
		}

		public function testConfig($key) {
			return $this->$key;
		}

		public function testToString() {
			return $this->__toString();
		}

		public function addUser($email, $password) {
			$auth = new auth();
			return $auth->addUser($email,$password);
		}

		public function getUser($email) {
			global $config;
			$users 		= $config['users'];
			return $users->$email;
		}

		public function dialog($page) {
			return ui::dialog($page, []);
		}

	}

?>