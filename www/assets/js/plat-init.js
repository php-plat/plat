

/** Init APIS **/
init_events();
init_notes();


/**
 * Events API
 */
function init_events() {
	events

		.listen('ping', function(event, data) {
			$('.server-time').html(data['server-time']);

			var $icon1 		= $("<span class=\"glyphicon glyphicon-download\"></span>");
			var $icon2 		= $("<span class=\"glyphicon glyphicon-download\"></span>");
			var $icon3 		= $("<span class=\"glyphicon glyphicon-download\"></span>");

			var load5 		= $icon1.css("transform", "rotate("+data['load'][0]+"deg)");
			var load10 		= $icon2.css("transform", "rotate("+data['load'][1]+"deg)");
			var load15 		= $icon3.css("transform", "rotate("+data['load'][2]+"deg)");

			$('.server-load.load-5-i').html(load5);
			$('.server-load.load-10-i').html(load10);
			$('.server-load.load-15-i').html(load15);

			$('.server-load.load-5').html(((data['load'][0] / Math.PI) / 360).toFixed(2));
			$('.server-load.load-10').html(((data['load'][1] / Math.PI) / 360).toFixed(2));
			$('.server-load.load-15').html(((data['load'][2] / Math.PI) / 360).toFixed(2));
		})

		.listen('message', function() {});
	;
}


/**
 * Notes API
 */
function init_notes() {}