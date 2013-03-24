$(document).ready(function(){
	
	// load the rules table
	load_rules_table(false);
	
	// listener for rule type tooltip listens even on new objects
	$("#rules-list tbody").on("click", "a.rule-type-tooltip", function(e){
		e.preventDefault();
		var data_source = $(this).data('source');
		var charge_id = $(this).data('chargeid');
		var popoverhtml = "<ul>";
		var element_to_append = this;
		$.get(site_url+"/admin/contract/getruleoptions/"+charge_id+"/"+data_source, function(options){
			for (var key in options)
			{
			   if (options.hasOwnProperty(key))
			   {
					console.log("appending", options[key].name);
					popoverhtml += "<li>"+options[key].name+"</li>";
				}
			}
			popoverhtml += "</ul>";
			var popover = "<div'>"+popoverhtml+"</div>";
			$(element_to_append).after(popover);
		});
	});
	
	// listener when rule selection changes
	$("#rule_application").change(function(){
		clear_port_rules();
		var verb = $(this).find(':selected').data('verb');
		var source = $(this).find(':selected').data('source');
		$("#rule-application-entry > div.verb").html(verb);
		// clear out entry area
		$("#rule-application-entry > div.entry").html("");
		switch(source){
			case 1: 
				// source is cities
				create_cities_list((verb == "are") ? true : false);
				break;
			case 2:
				// source is countries
				create_countries_list((verb == "are") ? true : false);
				break;
			case 3:
				// source is ports
				create_ports_list((verb == "are") ? true : false);
				break;
			case 4:
				// source is container types
				create_containers_list();
				break;
			default:
				break;
		}
	}); // end rule change listener
	
	// save rule button listener
	$("button#saverule").click(function(e){
		e.preventDefault();
		// save the rule
		save_contract_rules();
	})
});


/**
* save the contract entered onto the page
*/
function save_contract_rules () {
	var name = $("#name").val();
	var rule_application_type = $("#rule_application_type").val();
	var currency = $("#currency").val();
	var value = $("#value").val();
	var rule_application = $("#rule_application").val();
	var code = $("#code").val();
	// get the filled in selec2 box values
	var rule_application_cases = $("#rule-app-add").select2("val");
	
	var data = {
		contract_id : contract_id,
		name : name,
		code: code,
		application_type : rule_application_type,
		currency : currency,
		value : value,
		application_rule : rule_application,
		application_cases : rule_application_cases
	}
	
	console.log("data", data);
	
	var saving_rule = $.post(site_url+"/admin/contract/saverule", data);
	
	saving_rule.done(function (data){
		load_rules_table(true);
	});
	
	
}

function clear_port_rules () {
	$("#rule-app-add").remove();
	$("#rule-application-entry > div.values > ul").remove();
}

function create_port_rules_list() {
	$("#rule-application-entry > div.values").append("<ul><ul>");
}


// list of countries input box
function create_countries_list (multiple_rule) {
		// set label and create select box
		var label = $('<label>Select Country to Add to list of countries affected by this rule</label>');
		//var select = $('<select id="rule-app-add" multiple style="width:220px;"></select>');
		var select2box = $('<input type="hidden" class="bigdrop" id="rule-app-add" style="width:220px"/>')
		// add elements to page
		$(label).appendTo("#rule-application-entry > div.entry");
		$(select2box).appendTo("#rule-application-entry > div.entry");
		// get list of countries from service
		$.get(site_url+"/services/list_of_countries", function(countries){
			// use data returned from get array to make select2 element
			$("#rule-app-add").select2({
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


function create_cities_list (multiple_rule) {
	// set label and create select box
	var label = $('<label>Select City to Add to list of cities affected by this rule</label>');
	//var select = $('<select id="rule-app-add" multiple style="width:220px;"></select>');
	var select2box = $('<input type="hidden" class="bigdrop" id="rule-app-add" style="width:440px"/>')
	// add elements to page
	$(label).appendTo("#rule-application-entry > div.entry");
	var page_size = 10;
	$(select2box).appendTo("#rule-application-entry > div.entry");
	$("#rule-app-add").select2({
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
									+"<tr><td class='muted'>"+city.country_name+"</td></tr></table></td></tr></table>";
		return formatted_html;
		}					
}

function format_city (city) {
	return city.city_name+((city.state==null) ? "" : ", "+city.state)+", "+city.country_code;
}

function create_ports_list (multiple_rule) {
	// set label and create select box
	var label = $('<label>Select Port to Add to list of ports affected by this rule</label>');
	//var select = $('<select id="rule-app-add" multiple style="width:220px;"></select>');
	var select2box = $('<input type="hidden" class="bigdrop" id="rule-app-add" style="width:440px"/>')
	// add elements to page
	$(label).appendTo("#rule-application-entry > div.entry");
	var page_size = 10;
	$(select2box).appendTo("#rule-application-entry > div.entry");
	$("#rule-app-add").select2({
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
		
		var formatted_html = "<table>"
								+"<tr>"
									+"<td><img class='flag' src='"+base_url+"assets/img/flags_iso/64/" + port.country_code.toLowerCase() + ".png' /></td>"
		 							+"<td><table><tr><td><strong>"+port.name+"</strong></td></tr>"
									+"<tr><td class='muted'>"+port.country_code+port.port_code+"</td></tr></table></td></tr></table>";
		return formatted_html;
		}					
}

function format_port (port) {
	return port.name+", "+port.country_code;
}


function create_containers_list () {
	$.get(site_url+"/services/list_of_container_types/"+contract_id, function(results){
		var select = "<select>";
		for (var key in results)
		{
		   if (results.hasOwnProperty(key))
		   {
		     	var option = "<option value='"+results[key].id+"'>"+results[key].container_type+" - "+results[key].description+"</option>";
				select += option;
		   }
		}
		select += "</select>";
		$(select).appendTo("#rule-application-entry > div.entry");
	});
}

function load_rules_table (clear) {
	if(clear)
		$("#rules-list tbody").html('');
	$.get(site_url+"/admin/contract/getrules/"+contract_id, function(rules){
		for (var key in rules)
		{
		   if (rules.hasOwnProperty(key))
		   {
		      // here you have access to
		      var id = rules[key].id;
		      var name = rules[key].name;
		      var application_type = rules[key].application_type;
		      var currency = rules[key].currency;
			  var value = rules[key].value;
		      var rule_type = rules[key].rule_type;
		      var data_source = rules[key].data_source;
			  $("#rules-list tbody").append("<tr><td>"+name+"</td>"
				+"<td>"+application_type+"</td>"
				+"<td><a class='rule-type-tooltip' href='#' data-source='"+data_source+"' data-chargeid="+id+">"+rule_type+"</a></td>"
				+"<td>"+currency+"</td>"
				+"<td>"+value+"</td>"
				+"</tr>");
		   }
		}
	});
}