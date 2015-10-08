<?php

	class guestServices extends plugin {

		public function __constrcut() {
			parent::__construct('guestServices');
		}

		public function dialog($page) {
			return ui::dialog($page, []);
		}

		public function auth($username, $password) {
			$auth 	= new auth();
			return $auth->authUser($username, $password);
		}

		public function signout() {
			unset($_SESSION['token']);
			return true;
		}

		public function authenticated() {
			$auth 			= new auth();
			return $auth->authenticated();
		}

		public function ux($page, $param) {
			global $pageParam;

			$auth 			= new auth();
			$authenticated 	= $auth->authenticated();
			$pageParam 		= $param;

			$folder 		= ($authenticated) ? 'users' : 'public';
			$path 			= "../ui/$folder/$page.php";
			
			if (!realpath($path))return false;
			return file_get_contents($path);
		}

		public function application() {
			global $config;
			$sys 	= $config['system'];
			return $sys->application;
		}

		public function version() {
			global $config;
			$sys 	= $config['system'];
			return $sys->version;
		}


	}

?>