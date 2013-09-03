


/**
 * drop down for port and port groups
 */
var dropdown_datasets = [
 {
	name : 'port_groups',
	header : '<h5>Port Groups</h5>',
	remote : {
		url : site_url + "/services/search_port_groups/"+contract_id+"/%QUERY",
		filter : function(parsedResponse) {
			return parsedResponse.map(transform_port_group_to_datum);
		}
	},
	limit : 3
},{
	name : 'ports',
	header : '<h5>UN Port</h5>',
	remote : {
		url : site_url + "/services/search_ports/%QUERY",
		filter : function(parsedResponse) {
			return parsedResponse.map(transform_port_to_datum);
		}
	},
	template : base_url + 'assets/templates/port_suggestion.ejs',
	engine : ejs,
	limit : 5
}];



$(document).ready(function(){
	$('#origin, #destination').typeahead(dropdown_datasets).on('typeahead:selected', function(event, datum) {
		$(this).data("type", datum.type).data("value", datum.id);
		if(datum.type == 'port'){
			$.post(site_url+'/services/increment_port_hit_count',{port_id: datum.id});
		}
	});
	
	
	$("#save").click(function(){
		var data = {
			origin: $("#origin").data('value'),
			origin_type: $("#origin").data('type'),
			destination: $("#destination").data('value'),
			destination_type: $("#destination").data('type'),
			cargo: $("#cargo_type").val(),
			effective_date: $("#effective").val(),
			expires: $("#enddate").val(),
			contract_id: contract_id
		};
		
		var containers = new Array();
		$("input.container-value").each(function(){
			if($(this).val() != ''){
				var container = 
				{
					type: $(this).data('container-type'),
					value: $(this).val(),
					currency: $(this).siblings("select").val()
				};
				containers.push(container);
			}
			
		});
			
		data['containers'] = containers;
		data['items'] = LineItemCalculator.product;
		//console.log("Data", data, "Line Items:", LineItemCalculator.product);
		
		$.post(site_url+"/admin/line/save", data).done(function(){
			console.log("Complete");
		});	
	});
});
