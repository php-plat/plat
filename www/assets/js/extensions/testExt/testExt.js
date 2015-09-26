var testExt = function() {

	var self 		= this;
	var api 		= new platAPI("test");

	this.init 		= function() {
		platAPI.prototype.textExt = self;
		return this;
	};

	this.sendNote 	= function() {
		api.notify("test", "tester");
	};

	return this.init();
}