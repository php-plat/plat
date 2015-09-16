<?php 

	final class events {

		protected $data 		= array();
		protected $ready		= false;

		function __construct() {
			$this->dataPath = '../www/events/events.data';
			$this->ready 	= $this->read();
		}

		public function add($eventName, $eventData, $id = null) {
			if (!$id) $id = session_id();
			$this->read();

			$this->data['events'][$id][]	= [
				'event' 	=> $eventName,
				'data'		=> $eventData,
				'created'	=> microtime(true),
				'id'		=> $id
			];

			return ($this->write() != false);
		}

		private function read() {			
			if (!file_exists($this->dataPath)) {
				try {
					touch($this->dataPath);
				} catch (Exception $e) {
					throw new exception("Cannot create file: {$this->dataPath}");
				}
			}

			$content 		= file_get_contents($this->dataPath);
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
			$serialized 	= serialize($this->data);
			$encoded 		= base64_encode($serialized);

			if (file_put_contents($this->dataPath, $encoded)) return $this->data;
			return false;			
		}

	}

?>