<?php

	class config {

		protected $folder;
		protected $path;
		protected $data;

		private $config;

		public $ready		= false;
		public $name 		= '';

		public function __construct($folder, $name) {
			$this->folder 	= realpath($folder);
			$this->path 	= "$folder/$name.conf";
			$this->ready 	= file_exists($this->path);
			$this->name 	= $name;

			if (!$this->ready) {
				$created 		= $this->createConfigFile();
				$this->ready 	= (file_exists($this->path) and $created);
				if (!$this->ready) return false;
			}

			$this->ready 		= (file_exists($this->path) and is_writable($this->path));
			$this->read();
		}

		public function __toString() {
			return $this->json();
		}

		public function json() {
			return json_encode($this->data);
		}

		public function __get($key) {
			return (isset($this->data[$key])) ? $this->data[$key] : null;
		}

		public function __set($key, $value) {
			$this->data[$key]	= $value;
			$this->write();
		}

		public function __invoke($key = null, $value = null) {
			if ($key and $value) {
				$this->$key = $value;
				return $this->$key;
			} elseif($key and !$value) {
				return $this->$key;
			} else {
				return $this->data;
			}
		}

		public function get($key) {
			return $this->__get($key);
		}

		public function id() {return (isset($this->config['id'])) ? $this->config['id'] : null;}
		public function version() {return (isset($this->config['version'])) ? $this->config['version'] : null;}
		public function name() {return $this->name;}
		public function created() {
			return (isset($this->config['created'])) 
				? date("Y-m-d H:i:s T", strtotime($this->config['created']))
				: null
			;
		}

		private function createConfigFile() {
			if (!touch($this->path)) return false;

			$content		= [
				'version'		=> '1.0',
				'updated'		=> microtime(true),
				'id'			=> uniqid(),
				'data'			=> [
					'created'	=> microtime(true)
				]
			];

			$serialized	= json_encode($content);
			//$encoded	= base64_encode($serialized);
			//$bytes 		= file_put_contents($this->path, $encoded);
			$bytes 		= file_put_contents($this->path, $serialized);
			return ($bytes !== 0 and $bytes !== false);
		}

		private function read() {
			if (!$this->path) return false;

			$encoded 	= file_get_contents($this->path);
			if (!$encoded) return false;

			$serialized	= json_decode($encoded, true);
			if (!$serialized)  return false;

			$config 	= $serialized;

			//$config 	= unserialize($serialized);
			//if (!$config) return false;

			$this->data 	= (isset($config['data'])) ? $config['data'] : [];
			$this->ready 	= (is_array($this->data));
			
			unset($config['data']);
			$this->config 	= $config;

			return $this->data;
		}

		private function write() {
			if (!$this->path) return false;

			$content		= [
				'version'		=> '1.0',
				'updated'		=> microtime(true),
				'id'			=> uniqid(),
				'data'			=> $this->data
			];

			$serialized	= json_encode($content);
			//$encoded	= base64_encode($serialized);
			//$bytes 		= file_put_contents($this->path, $encoded);
			$bytes 		= file_put_contents($this->path, $serialized);

			if ($bytes !== 0 and $bytes !== false) $this->read();
			return $this->ready;
		}

	}

?>