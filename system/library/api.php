<?php

	class api {

		protected $plugins	= [];
		
		public function __construct() {
			$this->events 	= new events();
			$this->notes 	= new notes();
		}

		public function __get($key) {
			return (isset($this->plugins[$key])) ? $this->plugins[$key] : null;
		}

		public function __set($key, $value) {
			$this->plugins[$key]	= $value;
		}
	}

?>