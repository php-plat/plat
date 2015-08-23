/**
 * Events API
 */
var eventAPI = function() {

	var self 		= this;
	var source 		= '';

	this.init 		= function() {

		if (!("EventSource" in window)) {
 			console.log("This browser does not support server-side events for some reason.");
 		} else {
 			source	= new EventSource("events");
		}

		return this;
	};

	this.listen 	= function(eventName, callback) {
		source.addEventListener(eventName, function(event) {
			var data = JSON.parse(event.data);

			if (self.isMessageEvent(data) || eventName == 'message') {
				notes.notify(data.title, data.message, data, callback(event, data));
			} else {
				callback(event, data);
			}
		}, false);

		return this;
	};

	this.isMessageEvent	= function(data) {
		return (data.title && data.message);
	};

	return this.init();
};

var events 	= new eventAPI();