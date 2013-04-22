
$(document).ready(function(){
	
	
	// initialize datepicker
	$('.datepicker').datepicker();

	var page_size = 10;
	// initialize selec2 on origin and destination
	$("#location").select2({
		placeholder: "Search for a city",
		multiple: false,
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


	// add leg to route
	$("#add-leg").click(function(){
		var location_id = $("#location").select2("val");

		var leg_type = $("#leg-type").val();
		var leg_name = $("#leg-type option[value='"+leg_type+"']").text();
		var transport_type = $("#transport-type").val();
		var transport_name = $("#transport-type option[value='"+transport_type+"']").text();
		var name = $("#location").select2("data").name + ", "+$("#location").select2("data").country_code;
		var html = "<tr data-location="+location_id+" data-leg-type="+leg_type+" data-transport="+transport_type+">";
		html+= "<td>"+name+"</td>";
		html+= "<td>"+leg_name+"</td>";
		html+= "<td>"+transport_name+"</td>";
		html+= '<td><button class="btn btn-danger btn-mini delete-route" href="#"><i class="icon-trash icon"></i> Delete</button></td>';
		html += "</tr>";
		
		$("#route").append(html);
		
	});
	
	// clear button click
	$("#clear-lane").click(function(){
		clear_lane_input();
	});
	
	// handle delete route click
	$("#route").on("click", "button.delete-route", function(){
		$(this).parents("tr").remove();
	});
	
	
	
	// currency code change handler
	$("#currency_code").change(function(){
		$("#amount").siblings("span.add-on").html($(this).find(":selected").data("symbol"));
	});
	
	
	// delete lane on delete button click
	$("#lanes").on("click", "button.lane-delete", function(){

		var data = { lane_id : $(this).parents("tr").data('id') };
		$.post(site_url+"/admin/contract/deletelane", data).done(function(){ load_lanes(); });
	});
	
	// handle add lane
	$("#add-lane").click(function(){
		
		var legs = [];
		
		$("#route tr:not(:first)").each(function(index, value){
			
			var leg = {
				transport: $(this).data("transport"),
				leg_type: $(this).data("leg-type"),
				location: $(this).data("location")
			};
			legs.push(leg);
		})
		
		var data = {
			contract_id : $("#contract_id").val(),
			container_type : $("#container_type").val(),
			cargo_type : $("#cargo_type").val(),
			effective_date : $("#effective_date").val(),
			legs : legs,
			value : $("#amount").val(),
			currency : $("#currency_code").val()
		};
		
		// save data
		$.post(site_url+"/admin/contract/savelane", data)
		.done(function(data){
			load_lanes();
		});
	});
	
	
	// load lanes
	load_lanes();
	
});

function clear_lane_input(){
	$("#route tr:not(:first)").remove();
	$("#transport-type").val(0);
	$("#leg-type").val(0);
	$("#container_type").val(0);
	$("#currency_code").val(5);
	$("#amount").val("");
	$("#cargo_type").val(0);
}


/*
* load lanes from database
*/
function load_lanes(){
	
	// clear table
	$("#lanes tr").remove();
	
	var contract_id = $("#contract_id").val();

	$.get(site_url+"/admin/contract/getlanes/"+contract_id, function(data){
		
		var html = "";
		for(var index = 0; index < data.length; index++)
			html += new EJS({url: base_url+'assets/templates/admin/contract/lane.ejs'}).render(data[index]);
		$("#lanes").append(html);
		// enable popover
		$("a.desc").popover();
	});
	
	
}
