<?php

	class apiRequest {

		public $class;
		public $method;
		public $type;
		public $result;

		protected $data;

		public function __construct($class, $method, $type, $data = array()) {
			$this->class 	= $class;
			$this->method 	= $method;
			$this->type 	= $type;

			if ($data and !is_array($data)) $data = [$data];
			$this->data 	= $data;
		}

		public function __get($key) {
			return (isset($this->data[$key])) ? $this->data[$key] : null;
		}

		public function __set($key, $value) {
			$this->data[$key]	= $value;
		}

		public function __toString() {
			return $this->json();
		}

		public function __invoke($key = null, $value = null) {
			if ($key and $value) {
				$this->$key = $value;
				return $this->$key;
			} elseif ($key and !$value) {
				return $this->$key;
			} else {
				return $this->data;
			}
		}

		public function json() 		{return json_encode($this->data);} 
		public function data() 		{return $this->data;}
		public function encoded() 	{return base64_encode(serialize($this->data));}

		public function decode($encoded = null) {
			$encoded 	= ($encoded) ? $encoded : base64_encode(serialize([]));
			return unserialize(base64_decode($encoded));
		}
	}

	class api {

		protected $apiRequests;

		public function __construct() {
			$this->apiRequests 	= [];
		}

		public function addRequest(apiRequest $request) {
			$urid 						= uniqid(session_id());
			$this->apiRequests[$urid]	= $request;
			return $urid;
		}

		public function push(array $results) {
			foreach ($results as $urid => $result) {
				if (!array_key_exists($urid, $this->apiRequests)) continue;

				$request 					= $this->apiRequests[$urid];
				$request->result 			= $result;
				$this->apiRequests[$urid]	= $request;
			}
		}

		public function __invoke($push = null) {
			if ($push) $this->push($push);
			return $this->pending();
		}

		public function pending() {
			return $this->apiRequests;
		}
	}

?>