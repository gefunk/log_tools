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
	
	
	/**
	* handle adding a condition
	*/
	$("#add-charge-condition").click(function(){
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
	
	
	/** retrieve lanes for conditions **/
	$("#get-lanes-affected").click(function(){
		get_lanes_affected_by_rule();
	});
	
	
	
	// handlers to show and delete an individual condition
	/** mouse enters show the delete **/
	$("section#rule").on("mouseenter", "li", function(){
		$(this).children("span.delete-condition").show();
	});
	// mouse leaves - hide it
	$("section#rule").on("mouseleave", "li", function(){
		$(this).children("span.delete-condition").hide();
	});
	
	$("section#rule").on("click", "span.delete-condition", function(){
		$(this).parent("li").remove();
	});

	
	
	
});



function get_lanes_affected_by_rule () {
	// retrieve all the conditions and values
	var conditions = new Array();
	// read all the rule conditions
	$("ul#condition-list > li").each(function(){
		
		var condition = {
			values : []
		}
		
		condition.id = $(this).attr('data-rule-id');
		// add rule id to conditions array
		
		var values = new Array();
		// read all the rule values
		$(this).children("span.selected-text").children("span").each(function(key, value){
			var value_id = $(value).attr('data-id');
			// add value to values array
			condition.values.push(value_id);
		});
		// set this condition to have these values
		conditions.push(condition);
	});
	$.post(site_url+"/admin/contract/getlanesaffected", {conditions: JSON.stringify(conditions)})
	.done(function(data){
		console.log("data returned");
		var $div = $("div#affected-lanes");
		$div.html("").hide();
		var html = "";
		for(var index = 0; index < data.length; index++){
			html += new EJS({url: base_url+'assets/templates/admin/contract/lane.ejs'}).render(data[index]);
			
		}
			
		
		$div.append(html);
		// enable popover
		$("a.desc").popover();
		
		$div.show("slow");
	});
}



/** UTILITIES **/


/**
* returns formatted sentences for conditions which have multiple
* values
* @param selections the different selection values e.g. India, USA, etc...
* @param formatter the formatter to use to return the text of the values, 
* same formatter used in selec2
* 
*/
function get_text_for_multiple (selections, formatter) {
	var text = "";
	for(var se in selections){
		text += "<span data-id='"+selections[se].id+"'>"+
		window[formatter](selections[se])+"</span>"+" and ";
	}
	
	// strip trailing and
	return text.substring(0, text.lastIndexOf(" and "));
}


/**
* returns the formatter based on what the source for the rule was
* @param source the source from the database attached to the rule,
* the switch statement cases are directly related to the source
* in the db
*/
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