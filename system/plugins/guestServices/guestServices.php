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

		public function register($email, $password, $confirm) {
			$email 		= trim($email);
			$password 	= trim($password);
			$confirm 	= trim($confirm);

			if (!$email or !$password) 	return false;
			if ($password == $email) 	return false;
			if ($password !== $confirm)	return false;
			if (strlen($password) < 8)	return false;

			$auth 		= new auth();
			return $auth->addUser($email, $password);
		}

		public function performPasswordReset() {
			global $core;

			$auth 		= new auth();
			$token 		= $_REQUEST['token'];

			$email		= $auth->getUserByToken($token);
			if (!$email) return false;

			$user 		= $auth->getUser($email);
			if (!$user) return false;

			$_SESSION['password_reset']	= true;
			return true;
		}

		public function resetPassword($email) {
			global $core;

			$email 			= trim($email);
			if (!$email) 	return false;

			$auth 			= new auth();
			if (!$auth->userExists($email)) return false;

			$user 			= $auth->getUser($email);
			$host 			= $_SERVER['HTTP_REFERER'];
			$to      		= $email;
			$subject 		= 'PGE Password Reset';
			$message 		= "
				<strong>Plat Game Engine: Password reset</strong>
				<hr>
				<a href=\"{$host}post.php?token={$user['api']}&class=guestServices&method=performPasswordReset&data[token]={$user['api']}\">Reset Now</a>
			";

			$from 			= 'PGE <pge@kuhlonline.com>';
		
    		$mailer 		= new mailer();
			return $mailer->send($from, $to, $subject, $message);			
		}

		public function changePassword($token, $password) {
			$auth 			= new auth();
			return $auth->changePassword($token, $password);
		}

		public function ux($page, $param = array()) {
			global $pageParam;

			$auth 			= new auth();
			$authenticated 	= $auth->authenticated();
			$pageParam 		= $param;

			$folder 		= ($authenticated) ? 'users' : 'public';
			$path 			= "../ui/$folder/$page.php";
			
			if (!realpath($path)) return false;

			ob_start();
				include($path);
				$html 	= ob_get_clean();

			if ($_SESSION['password_reset']) {
				$_SESSION['password_reset'] = false;
				$html .= "<script>API.dialog('reset.enter', '');</script>";
			}

			return $html;
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

		public function tagline() {
			global $config;
			$sys 	= $config['system'];
			return $sys->tagline;
		}


	}

?>