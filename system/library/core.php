<?php

	class core {

		protected $plugins	= [];
		protected $api 		= null;
		protected $results	= [];

		public $log;
		public $errors;
		
		public function __construct(api &$api = null, array $plugableLibraries = array(), array $plugins = array()) {
			$this->api 					= $api;
			
			$this->init_plugableLibraries($plugableLibraries);
			$this->init_plugins($plugins);
		}

		private function init_plugableLibraries(array $plugableLibraries = array()) {
			foreach ($plugableLibraries as $lib) {
				$this->plugins[$lib]	= new $lib();
			}
		}

		private function init_plugins(array $plugins = array()) {
			foreach ($plugins as $name => $plugin) {
				$this->plugins[$name]	= $plugin;
			}
		}

		public function __get($key) {
			return (isset($this->plugins[$key])) ? $this->plugins[$key] : null;
		}

		public function __set($key, $value) {
			$this->plugins[$key]	= $value;
		}

		public function __invoke() {
			return $this->process();
		}

		public function processRequests() {
			if (!$this->api) return false;

			$requests 	= $this->api->pending();
			foreach ($requests as $urid => $request) {

				if (!$this->hasPlugin($request->class)) {
					$this->error("Plugin {$request->class} does not exist");
					$this->results[$urid]	= [
						'result'		=> false,
						'okay'			=> false,
						'id'			=> $urid,
						'timestamp'		=> time(),
						'microtime'		=> microtime(true)
					];

					$this->api->push($this->results);
					continue;
				}

				$requestObject 	= $this->plugins[$request->class];
				if (is_array($requestObject)) {
					$plugin			= $request->class;
					$requestObject 	= new $plugin();
				}

				if (!method_exists($requestObject, $request->method)) {
					$this->error("Method {$request->method} does not exist on {$request->class}");
					$this->results[$urid]	= [
						'result'		=> false,
						'okay'			=> false,
						'id'			=> $urid,
						'timestamp'		=> time(),
						'microtime'		=> microtime(true)
					];

					$this->api->push($this->results);
					continue;
				}

				$requestMethod	= $request->method;
				$requestData 	= $request->data();
				$requestResult	= call_user_func_array([$requestObject, $requestMethod], $requestData);
				//$requestResult	= $requestObject->$requestMethod($requestData);

				$this->log("Requested {$request->class}->$requestMethod");
				//$this->log(print_r($requestResult, true));

				$this->results[$urid]	= [
					'result'		=> $requestResult,
					'okay'			=> true,
					'id'			=> $urid,
					'timestamp'		=> time(),
					'microtime'		=> microtime(true)
				];
			}

			$this->api->push($this->results);
			return $this->results;
		}

		public function result($resultId) {
			return (isset($this->results[$resultId]))
				? $this->results[$resultId]
				: null
			;
		}

		public function pluginsList() {
			return array_keys($this->plugins);
		}

		public function hasPlugin($pluginName) {
			return array_key_exists($pluginName, $this->plugins);
		}

		protected function error($errorMessage, $source = null) {
			$source 					= ($source) ? $source : 'system';
			$this->errors[$source][]	= $errorMessage;
		}

		protected function log($msg) {
			$this->log[] = $msg;
		}

	}

?>