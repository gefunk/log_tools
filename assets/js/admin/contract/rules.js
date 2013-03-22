$(document).ready(function(){
	$("#rule_application").change(function(){
		clear_port_rules();
		var verb = $(this).find(':selected').data('verb');
		var source = $(this).find(':selected').data('source');
		$("#rule-application-entry > div.verb").html(verb);
		switch(source){
			case 1: 
				// source is cities
				break;
			case 2:
				// source is countries
				$.get(base_url+"/services/list_of_countries", function(data){
					$("#rule-application-entry > div.entry").html("");
					var label = $('<label>Select Country to Add to list of countries affected by this rule</label>');
					var select = $('<select id="rule-app-add"></select>');
					for (var key in data)
					{
					   if (data.hasOwnProperty(key))
					   {
					      // here you have access to
					      var country_name = data[key].name;
					      var country_id = data[key].id;
						  select.append('<option value="' + country_id + '">' + country_name + '</option>');
					   }
					}
					var button = $("<button class='btn btn-small'>Add</button>")
					$(label).appendTo("#rule-application-entry > div.entry");
					$(select).appendTo("#rule-application-entry > div.entry");
					$(button).appendTo("#rule-application-entry > div.entry");
					create_port_rules_list();
				});
				break;
			case 3:
				// source is ports
				
				break;
			case 4:
				// source is container types
				break;
			default:
				break;
		}
	}); // end rule change listener
	
	/*
	* add element from select entry
	* will have to adjust when input is type text
	*/
	$("#rule-application-entry > div.entry").on("click", "button", function(event){
		event.preventDefault();
		var option_selected = $("#rule-app-add").find(":selected");
		var country_text = option_selected.text();
		var country_id = option_selected.val();
		var entry = "<li data-id='"+country_id+"'><span>"+country_text+"</span><button class='btn btn-danger btn-mini'>Delete</button></li>";
		$("#rule-application-entry > div.values > ul").append(entry);
	});
	
	/**
	* remove anything from list when added to list
	*/
	$("#rule-application-entry > div.values > ul").on("click", "button", function(event){
		event.preventDefault();
		$(this).parent('li').remove();
	});
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
	var rule_application_cases = new Array();
	var code = $("#code").val();
	// loop through all the li's
	$("#rule-application-entry > div.values > ul > li").each(function(){
		// add each li to array
		rule_application_cases.push($(this).data('id'));
	});
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
	
	var saving_rule = $.post(base_url+"/admin/contract/saverule", data);
	
	saving_rule.done(function (data){
		console.log("Done");
	});
	
	
}

function clear_port_rules () {
	$("#rule-app-add").remove();
	$("#rule-application-entry > div.values > ul").remove();
}

function create_port_rules_list() {
	$("#rule-application-entry > div.values").append("<ul><ul>");
}
