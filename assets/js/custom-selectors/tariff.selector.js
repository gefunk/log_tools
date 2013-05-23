
function create_tariff_list (id) {
	$.get(site_url+"/services/list_of_tariffs/"+carrier_id, function(tariffs){
		// get list of countries from service
		$(id).select2({
			placeholder: "Select a tariff",
			data: {results: tariffs, text: 'description'},
			formatSelection: tariff_format,
			formatResult: tariff_format,
		});
	});
}


// helper functions to format country result
function tariff_format (tariff) {
	return tariff.code + " - " + tariff.name;
}
