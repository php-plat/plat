<?php

	class test extends plugin {

		public function __construct() {
			parent::__construct('test');
		}

		public function test($query) {
			return $this->query($query);
		}

	}

?>