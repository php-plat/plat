/** Handlers */
	function unsetHandlers(handlers, event) {
		for (var index in handlers) {
			var handler 	= handlers[index];
			$('.' + handler).unbind(event);
		}
	}

	function register_handlers() {

		var handlers = [
			'auth_signin',
			'auth_register',
			'auth_forgot',
			'auth_perform_auth',
			'auth_perform_signout',
			'dialog_cancel'
		];

		unsetHandlers(handlers, 'click');
		

		$('.auth_signin').on('click', function(event) {
			gsAPI.authPrompt();
		});

		$('.auth_perform_auth').on('click', function(event) {
			var username 	= $('#auth_username').val();
			var password 	= $('#auth_password').val();

			gsAPI.call('auth', [username, password], perform_auth);
		});

		$('.auth_perform_signout').on('click', function(event) {
			gsAPI.call('signout', [], function(data) {
				gsAPI.notify("Sign out!", "Signed out from " + gsAPI.user);
				gsAPI.clearDialog();
				init_home_page();
				check_authentication();
			});
		});

		$('.auth_perform_register').on('click', function(event) {
			var username 	= $('#auth_username').val();
			var password 	= $('#auth_password').val();
			var confirm 	= $('#auth_confirm').val();

			gsAPI.call('register', [username, password, confirm], perform_register);
		});

		$('.auth_perform_reset').on('click', function(event) {
			var username 	= $('#auth_username').val();

			gsAPI.call('resetPassword', [username], perform_reset);
		});

		$('.auth_complete_reset').on('click', function(event) {
			var token 		= $('#auth_token').val();
			var password 	= $('#auth_password').val();

			gsAPI.call('changePassword', [token, password], complete_reset);
		});

		$('.auth_register').on('click', function(event) {
			gsAPI.registerPrompt();
		});

		$('.auth_forgot').on('click', function(event) {
			gsAPI.forgotPrompt();
		});

		$('.dialog_cancel').on('click', function(event) {
			gsAPI.clearDialog();
		});
	}


/** UX  */
	function page(pageName, param) {
		gsAPI.page(pageName, param, '#content');
	}

	function init_home_page() {
		gsAPI.page('home', [], '#content', function(data) {
			check_authentication();
		});
	}


/** Authentication */
	function perform_auth(data) {
		if (data === true) {
			gsAPI.clearDialog();
			init_home_page();
			check_authentication();
		} else {
			gsAPI.dialog('auth.error');
		}
	}

	function perform_register(data) {
		if (data != false) {
			gsAPI.dialog('reg.success');
		} else {
			gsAPI.dialog('reg.error');
		}
	}

	function perform_reset(data) {
		if (data != false) {
			gsAPI.dialog('reset.success');
		} else {
			gsAPI.dialog('reset.error');
		}
	}

	function complete_reset(data) {
		if (data != false) {
			gsAPI.dialog('reset.complete');
		} else {
			gsAPI.dialog('reset.error');
		}	
	}

	function check_authentication() {

		gsAPI.call('authenticated', [], function(data) {

			if (typeof data == 'string') {
				gsAPI.authenticated 	= true;
			} else {
				gsAPI.authenticated 	= false;
			}

			gsAPI.user = data;
			
			if (gsAPI.authenticated != true) {
				$('.auth_signin').show();
				$('.auth_username').html('Guest');
				$('.auth_label').hide();
			} else {
				$('.auth_username').html(gsAPI.user);
				$('.auth_label').show();
				$('.auth_signin').hide();

				gsAPI.notify("Welcome Back!", "Signed in as " + gsAPI.user);
			}
		});	

		gsAPI.call('application', [], function(data) {
			$('.appName, title').html(data);
		});

		gsAPI.call('version', [], function(data) {
			$('.appVersion').html(data);
		});

		gsAPI.call('tagline', [], function(data) {
			$('.appTagline').html(data);
		});

	}


