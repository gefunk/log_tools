
// list of countries input box
function create_countries_list (id, multiple_rule) {

		// get list of countries from service
		$.get(site_url+"/services/list_of_countries", function(countries){
			// use data returned from get array to make select2 element
			$(id).select2({
				placeholder: "Search for a country",
				multiple: multiple_rule,
				data: {results: countries, text: 'name'},
				formatSelection: format_country,
				formatResult: format_country_result,
			});
		});
}

// helper functions to format country result
function format_country_result (country) {
	if(!country.code || country.code.toLowerCase() == 'bq' || country.code.toLowerCase() == 'sx') return country.name;
	else return "<img class='flag' src='"+base_url+"assets/img/flags_iso/24/" + country.code.toLowerCase() + ".png' style='margin-right: 3px' />" + country.name;
}

function format_country (country){
	return country.name;
}