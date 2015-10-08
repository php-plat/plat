/** Init APIS **/
var API 	= new platAPI();
var gsAPI	= new platAPI('guestServices');

$(document).on('ready', function(event) {

	register_handlers();

	init_home_page();	

	check_authentication();
	
});