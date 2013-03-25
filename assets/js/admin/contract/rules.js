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
		var appendto = "#rule-application-entry > div.entry";
		var id = "#rule-app-add";
		switch(source){
			case 1: 
				// source is cities
				var labelstr = "Select City to Add to list of cities affected by this rule";
				create_cities_list(labelstr, appendto, id, (verb == "are") ? true : false);
				break;
			case 2:
				// source is countries
				var labelstr = "Select Country to Add to list of countries affected by this rule";
				create_countries_list(labelstr, appendto, id, (verb == "are") ? true : false);
				break;
			case 3:
				// source is ports
				var labelstr = 'Select Port to Add to list of ports affected by this rule';
				create_ports_list(labelstr, appendto, id, (verb == "are") ? true : false);
				break;
			case 4:
				// source is container types
				create_containers_list(appendto);
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