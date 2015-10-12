<?php
	
	final class auth {

		protected $config;

		public function __construct() {
			global $config;

			$this->config 	= $config['users'];
		}

		public function addUser($username, $password) {
			if ($this->userExists($username)) return false;

			$token 		= $this->token($username, $password);
			$apiToken 	= md5($username.$token);

			$this->config->$username 	= [
				'username'		=> $username,
				'password'		=> $token,
				'api'			=> $apiToken
			];

			$this->config->$apiToken 	= $username;

			return $this->config->$username;
		}

		public function authUser($username, $password) {
			$combined 	= sha1($username.$password);
			$user 		= $this->config->$username;
			$hash 		= (isset($user['password'])) ? $user['password'] : false;

			if (!$user or !$hash) return false;

			$valid 		= password_verify($combined, $hash);
			if (!$valid) return false;

			$_SESSION['token']	= $user['api'];
			return true;
		}

		public function authenticated() {
			$token 	= (isset($_SESSION['token'])) ? $_SESSION['token'] : null;
			if (!$token) return false;

			$user 	= $this->config->$token;
			if (!$user) return false;
			
			return $user;
		}

		private function getUser($username) {
			return (isset($this->config->$username))
				? $this->config->$username
				: false
			;
		}

		public function removeUser($username) {
			$this->config->$username 	= null;
			return (is_null($this->config->$username));
		}

		public function changePassword($username, $oldPassword, $newPassword) {
			if (!$this->authUser($username, $oldPassword))  return false;

			$token 			= $this->token($username, $newPassword);
			$apiToken 		= md5($username.$token);

			$this->config->$username 	= [
				'username'		=> $username,
				'password'		=> $token,
				'api'			=> $api
			];

			$this->config->$apiToken 	= $username;

			return true;
		}

		public function apiToken($username) {
			$user 	= $this->getUser($username);

			return ($user['api'])
				? $user['api']
				: false
			;
		}

		public function verifyToken($apiToken) {
			$username 	= (isset($this->config->$apiToken))
				? $this->config->$apiToken
				: false
			;

			if (!$username) return false;

			$user 		= (isset($this->config->$username))
				? $this->config->$username
				: false 
			;

			if (!$user) return false;

			return ($user['api'] == $apiToken);
		}

		private function userExists($username) {
			return (isset($this->config->$username));
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