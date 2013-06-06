/*
*
* 
*/
function create_cities_list (id, multiple_rule) {
	console.log("Should be created", $(id));
	var page_size = 10;
	$(id).select2({
		placeholder: "Search for a city",
		multiple: multiple_rule,
		minimumInputLength: 4,
		ajax: {
		url: site_url+"/services/list_of_cities",
		dataType: 'json',
		quietMillis: 300,
		data: function (term, page) { // page is the one-based page number tracked by Select2
			return {
				query: term, //search term
				page_size: page_size, // page size
				page: page // page number
			};
		},
		results: function (data, page) {
			var more = (page * page_size) < data.total;
			// notice we return the value of more so Select2 knows if more results can be loaded
			return {results: data.results, more: more};
			}
		},
		formatResult: format_city_result, // omitted for brevity, see the source of this page
		formatSelection: format_city, // omitted for brevity, see the source of this page
		dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
		escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
	});
}

function format_city_result (city) {
	if(!city.country_code) return city.city_name;
	else{ 
		
		var formatted_html = "<table>"
								+"<tr>"
									+"<td><img class='flag' src='"+base_url+"assets/img/flags_iso/64/" + city.country_code.toLowerCase() + ".png' /></td>"
		 							+"<td><table><tr><td><strong>"+city.city_name+"</strong><span style='margin-left:3px'>"+((city.state==null) ? "" : ", "+city.state)+"</span></td></tr>"
									+"<tr><td class='country-dropdown'>"+city.country_name+"</td></tr></table></td></tr></table>";
		return formatted_html;
		}					
}

function format_city (city) {
	return city.city_name+((city.state==null) ? "" : ", "+city.state)+", "+city.country_code;
}
