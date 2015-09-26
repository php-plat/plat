<?php
	
	final class auth {

		protected $config;

		public function __construct() {
			global $config;

			$this->config 	= $config['users'];
			print_r($this);
		}

		public function addUser($username, $password) {
			if ($this->userExists($username)) return false;

			$token 		= $this->token($username, $password);
			$this->config->$username 	= [
				'username'		=> $username,
				'password'		=> $token
			];

			return $this->config->$username;
		}

		public function authUser($username, $password) {
			$combined 	= sha1($username.$password);
			$user 		= $this->config->$username;
			$hash 		= (isset($user['password'])) ? $user['password'] : false;

			if (!$user or !$hash) return false;
			return password_verify($combined, $hash);
		}

		public function removeUser($username) {

		}

		public function changePassword($username, $oldPassword, $newPassword) {

		}

		public function apiToken($username) {

		}

		private function userExists($username) {
			return false;
		}

		private function token($username, $password) {
			$combined 	= sha1($username.$password);
			$options 	= [
			    'cost' 	=> 11,
			    'salt' 	=> mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
			];

			$hash 		= password_hash($combined, PASSWORD_BCRYPT, $options);
			return $hash;
		}


	}

?>