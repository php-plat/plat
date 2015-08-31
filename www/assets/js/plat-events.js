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
 			source	= new EventSource("events/");
		}

		return this;
	};

	this.listen 	= function(eventName, callback) {
		source.addEventListener(eventName, function(event) {
			var data = JSON.parse(event.data);
			callback(event, data);
		}, false);

		return this;
	};

	return this.init();
};