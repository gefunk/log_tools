

/**
* creates a port list selec2 box
* @param appendto element to add the selec2 box
* @param id id of the select 2 element
* @param multiple_rule multiple selection for ports
* 
*/
function create_ports_list (labelstr, appendto, id, multiple_rule) {
	// if label is required
	if(labelstr){
		// set label and create select box
		var label = $('<label>'+labelstr+'</label>');
		// add elements to page
		$(label).appendTo(appendto);
		
	}
	// if use requires to create input on page
	if(appendto){
		//var select = $('<select id="rule-app-add" multiple style="width:220px;"></select>');
		var select2box = $('<input type="hidden" class="bigdrop" id="'+id+'" style="width:440px"/>')
		$(select2box).appendTo(appendto);
	}

	var page_size = 10;

	$(id).select2({
		placeholder: "Search for a port",
		multiple: multiple_rule,
		minimumInputLength: 4,
		ajax: {
		url: site_url+"/services/list_of_ports",
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
		formatResult: format_port_result, // omitted for brevity, see the source of this page
		formatSelection: format_port, // omitted for brevity, see the source of this page
		dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
		escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
	});
}

function format_port_result (port) {
	if(!port.country_code) return port.name;
	else{ 
		
		
		var icons = "<tr><td colspan=2>";
		
		if(port.ocean == 1){
			icons += "<span class='transport-icon transport-ocean' title='This is an ocean port'></span>";
		}
		if(port.airport == 1){
			icons += "<span class='transport-icon transport-air' title='There is an airport here'></span>";
		}
		if(port.rail == 1){
			icons += "<span class='transport-icon transport-rail' title='Rail Road Connection'></span>";
		}
		if(port.road){
			icons += "<span class='transport-icon transport-road' title='There is a road way here'></span>";
		}
		
		icons += "</tr></td>";
		
		var port_name = port.name;
		if(port.state){
			port_name += ", "+port.state+" ("+port.state_code+")";
		}
		
		var formatted_html = "<table>"
								+"<tr>"
									+"<td><img class='flag' src='"+base_url+"assets/img/flags_iso/64/" + port.country_code.toLowerCase() + ".png' /></td>"
		 							+"<td><table><tr><td><strong>"+port_name+"</strong></td></tr>"
									+"<tr><td class='muted'>"+port.country_code+port.port_code+"</td></tr></table></td></tr>"
									+icons+"</table>";
		return formatted_html;
		}					
}

function format_port (port) {
	var port_name = port.name;
	if(port.state)
		port_name += ", "+port.state;
	port_name += ", "+port.country_code;
	return port_name;
}
