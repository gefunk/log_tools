$(document).ready(function(){

	// set up selected when clicked
	$("#rule-selector > li > a").click(function(){
		var verb = $(this).data("verb");
		var source = $(this).data("source");
		var html = $(this).text()+" "+verb;
		$("#rule-desc").html(html);
		
		var appendto = "#rule-input";
		var multiple = (verb == "are") ? true : false;
		
		$(appendto).data("rule-info", 
			{
				id: $(this).data("id"), 
				desc: $(this).data("description"), 
				verb: $(this).data("verb"), 
				multiple: multiple,
				source: source,
				condition: $.trim($(this).text())
			}
		);

		var id = "#rule-entry";
		switch(source){
			case 1: 
				// source is cities
				create_cities_list(id, multiple);
				break;
			case 2:
				// source is countries
				create_countries_list(id, multiple);
				break;
			case 3:
				// source is ports
				create_ports_list(id, multiple);
				break;
			case 4:
				// source is container types
				create_containers_list(appendto);
				break;
			default:
				break;
		}
	});
	
	
	$("#add-charge-rule").click(function(){
		var rule_info = $("#rule-input").data("rule-info");
		
		// determine prefix to use
		var prefix = "AND";
		if($("ul#condition-list > li").length == 0){
			prefix = "WHEN";
		}
		
		rule_info.prefix = prefix;
		
		// determine if there are multiple elements in the selec2
		if(rule_info.multiple){
			var selections = $("#rule-entry").select2('data');
			var selection_text = get_text_for_multiple(selections, get_formatter_for_source(rule_info.source));
			rule_info.text = selection_text;
		}else{
			// single value
			rule_info.text = "<span data-id='"+$("#rule-entry").select2("val")+"'>"
								+window[get_formatter_for_source(rule_info.source)]($("#rule-entry").select2('data'))
							+"</span>";

			
		}
		
		console.log("Passing in this data", rule_info);
		var html = new EJS({url: base_url+'assets/templates/admin/contract/rules/condition.ejs'}).render(rule_info);
		
		// append to page
		/* check if ul already exists on page */
		if($("ul#condition-list").length == 0){
			// clear it out
			$("#conditions").html("<h4>Condition(s)</h4>");
			// append the ul
			$("#conditions").append("<ul id='condition-list'></ul>");
		}
		
		$("ul#condition-list").append(html);
		
	});
});


function get_text_for_multiple (selections, formatter) {
	var text = "";
	for(var se in selections){
		text += "<span data-id='"+selections[se].id+"'>"+
		window[formatter](selections[se])+"</span>"+" AND ";
	}
	return text;
}

function get_formatter_for_source (source) {
	var formatter = "";
	switch(source){
		// city
		case 1:
			formatter = "format_city";
			break;
		// country
		case 2:
			formatter = "format_country";
			break;
		// port
		case 3:
			formatter = "format_port";
			break;
	}
	return formatter;
}