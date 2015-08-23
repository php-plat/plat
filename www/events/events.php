<?php

	class events {

		protected $data 		= array();
		protected $ready		= false;

		function __construct() {
			$this->ready 	= $this->read();
		}

		public function add($eventName, $eventData, $id = null) {
			if (!$id) $id = session_id();

			$this->data['events'][$id][]	= [
				'event' 	=> $eventName,
				'data'		=> $eventData,
				'created'	=> microtime(true),
				'id'		=> $id
			];

			return $this->write();
		}

		public function sendEvents($id = null) {
			if (!$id) $id = session_id();
			
			$events 	= $this->data['events'][$id];
			$removed 	= [];

			foreach ($events as $index => $event) {
				$uid 	= uniqid($sid);
				$this->sendEvent($event['event'], $event['data'], $uid);
				$removed[]	= $index;
			}

			foreach ($removed as $index) {
				unset($events[$index]);
			}

			$this->data['events']	= $events;
			return $this->write();
		}

		protected function sendEvent($eventName, $data, $uid = null) {
			global $eventTime;

			$stream 	= 
				"event: $eventName\n".
				"id: $uid\n".
				"retry: $eventTime\n".
				"data: " . json_encode($data) .
				"\n\n"
			;

			print $stream;
			ob_flush();
		}

		private function read() {

			$dataPath 		= realpath('events/events.data');
			$content 		= file_get_contents($dataPath);
			if (!$content) 	return false;

			$decoded		= base64_decode($content);
			$valid 			= (base64_encode($decoded) == $content);
			if (!$valid) 	return false;

			$data 			= unserialize($decoded);
			if (!$data) 	return false;

			$this->data 	= $data;
			return true;
		}

		private function write() {

			$dataPath 		= realpath('events/events.data');
			$serialized 	= serialize($this->data);
			$encoded 		= base64_encode($serialized);

			return file_put_contents($dataPath, $encoded);
		}


	}


?>