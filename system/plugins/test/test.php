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

	}

?>