
var platAPI = function() {

	var self 		= this;

	this.events 	= function() {};
	this.notes 		= function() {};

	this.init 		= function() {

		this.events 	= new eventAPI();
		this.notes 		= new notifyAPI();

		this.init_events_api();
		
		return this;
	};

	this.init_events_api	= function() {
		this.events

			.listen('ping', function(event, data) {
				$('.server-time').html(data['server-time']);
				$('.server-load').html(data['server-load']);
			})

			.listen('message', function() {});
		;
	};


	return this.init();
};