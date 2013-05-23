
function create_containers_list (id) {
	$.get(site_url+"/services/list_of_container_types/"+carrier_id, function(containers){
		// get list of countries from service
		$(id).select2({
			placeholder: "Select a container",
			data: {results: containers, text: 'description'},
			formatSelection: container_format,
			formatResult: container_format,
		});
	});
}


// helper functions to format country result
function container_format (container) {
	return container.container_type + " - " + container.description;
}

