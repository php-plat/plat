/**
 * Notify API
 */
var notifyAPI = function() {

	var self 		= this;
	var enabled 	= false;

	this.init 		= function() {
		if (!("Notification" in window)) {
	    	console.log("This browser does not support desktop notifications for some reason.");
	  	} else if (Notification.permission === 'granted') {
	  		console.log("Notifications Permission Granted");
	  		enabled 	= true;
	  	} else if (Notification.permission !== 'denied') {
	  		this.requestPermission();
	  	}

		return this;
	};

	this.requestPermission	= function() {
		Notification.requestPermission(function(permission) {
  			if (permission === 'granted') {
  				console.log("Notifications Permission Granted");
  				enabled = true;
  			}
  		});

  		return this;
	};

	this.notify 	= function(title, message, data, callback) {
		var options 	= {
			body: 	message,
			icon: 	'assets/img/ico/plat-black.ico',
			tag: 	'Plat Framework'
		};

		var note 		= new Notification(title, options);
		note.data 		= data;
		note.onclick 	= function() {
			callback(note, data);
			note.close.bind(note);
		};

		setTimeout(note.close.bind(note), 5000); 
		return this;
	};

	return this.init();
};