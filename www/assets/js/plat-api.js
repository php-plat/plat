
var platAPI = function(contextRequest) {

	var self 		= this;
	this.context 	= contextRequest;

	this.apiSource 	= '';

	this.events 	= function() {};
	this.notes 		= function() {};

	this.init 		= function() {
		this.apiSource 	= './api/';
		this.events 	= new eventAPI();
		this.notes 		= new notifyAPI();

		this.init_events_api();
		return this;
	};

	/** Init Events API **/
	this.init_events_api	= function() {
		this.events

			.listen('ping', function(event, data) {
				$('.server-time').html(data['server-time']);
				$('.server-load').html(data['server-load']);

				$('.server-host').html(data.server.HTTP_HOST);
				$('.server-port').html(data.server.SERVER_PORT);
				$('.server-admin').html(data.server.SERVER_ADMIN);
				$('.server-name').html(data.server.SERVER_NAME);
				$('.server-addr').html(data.server.SERVER_ADDR);
			})

			.listen('message', function(event, data) {
				self.notes.notify(data.title, data.message, data);				
			});
		;
	};


	/** Notifcations **/
	this.notify 			= function(title, message, data, callback) {
		if (!this.notes.notify(title, message, data, callback)) return false;
		return true;
	};


	/** Event Hooks **/
	this.hook 				= function(eventName, callback) {
		if (!this.events.listen(eventName, callback)) return false;
		return true;
	};


	this.call 				= function(method, data, callback) {
		return this.send(this.context, method, data, callback);
	};


	/** API **/
	this.send 			= function(className, method, data, callback) {
		var payload 	= {
			'class': 	className,
			'method': 	method,
			'data': 	data,
			'type': 	'post'
		};

		var request 	= $.post(
			self.apiSource,
			payload,
			function(result) {
				var data 	= JSON.parse(result);
				if (typeof data.result != 'undefined') callback(data.result);
			}
		);

		return this;
	};

	this.request 			= function(className, method, callback) {
		var payload 	= {
			'class': 	className,
			'method': 	method,
			'type': 	'get'
		};

		var request 	= $.get(
			this.apiSource,
			payload,
			function(result) {
				var data 	= JSON.parse(result);
				if (typeof data.result != 'undefined') callback(data.result);
			}
		);

		return this;
	};

	/** Return initialized Self **/
	return this.init();
};