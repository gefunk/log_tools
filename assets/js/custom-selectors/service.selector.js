
function create_carrier_service_list (id) {
	$.get(site_url+"/services/list_of_tariffs/"+carrier_id, function(services){
		// get list of countries from service
		$(id).select2({
			placeholder: "Select a Service",
			data: {results: services, text: 'description'},
			formatSelection: carrier_service_format,
			formatResult: carrier_service_format,
		});
	});
}


// helper functions to format country result
function carrier_service_format (service) {
	return service.code + " - " + service.name;
}
