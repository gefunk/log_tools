
$(document).ready(function(){
	
	$("select").select2({width: '220px'});
	
	// set dates on input
	$("input#input_entry_start_date").datepicker('setValue', convert_sqldate_to_date(contract_start_date));
	$("input#input_entry_end_date").datepicker('setValue', convert_sqldate_to_date(contract_end_date));

	// initialize with location port drop down
	create_location_port_dropdown("location");
	
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
		$(this).closest("tr").remove();
	});
		
	// currency code change handler
	$("#currency_code").change(function(){
		$("#amount").siblings("span.add-on").html($(this).find(":selected").data("symbol"));
	});
	
	
	// delete lane on delete button click
	$("#lanes").on("click", "button.lane-delete", function(){
		$lanerow = $(this).parents("tr");
		var data = { lane_id : $lanerow.data('id') };
		$.post(site_url+"/admin/contract/deletelane", data).done(function(){ 
			$lanerow.hide('slow');
			$lanerow.remove(); 
		});
	});
	
	// Edit Lane
	
	// Show Rule input on Add Rule Button click
	$("#lanes").on("click", "button.lane-rule", function(){
		var $parent = $(this).parents('td');
		if($parent.hasClass("lane-rule-active")){
			$parent.removeClass('lane-rule-active');
			$parent.find("div.lane-rule-entry").parent("div").remove();
		}else{
			// retrieve list of charge codes
			$.get(site_url+"/services/list_of_charge_codes/"+carrier_id, function(results){
				var data = {
					charge_codes : results,
					currencies : currencies
				};
				var html = new EJS({url: base_url+'assets/templates/admin/contract/lane-rule-add.ejs'}).render(data);
				$parent.addClass("lane-rule-active").append(html);
				// add date picker to date field
				$parent.find("#rule-effective-date, #rule-expires-date").datepicker();
				// add what you see is what you get editor
				//$parent.find('textarea').wysihtml5();
				// attach click handler to upload file
				$parent.find("a.upload-file").click(function(){
					sendFiles($(this).siblings("input"));
				});
			});
			
			
		}
	});
	
	// Click handler for rule button delete
	$("#lanes").on("click", "button.delete-lane-charge", function(){
		var $row = $(this).parents("tr:first");
		console.log("This is the row I selected to delete", $row);
		var data = { charge_lane_id : $row.data("id") };
		$.post(site_url+"/admin/contract/deletelanecharge/", data)
		.done(function(data){
			// delete row from UI
			$table = $row.parents("table.lane-rules");
			$row.remove();
			if($table.children("tr").length <= 0)
				$table.remove();
		});


	});
	
	// handle add lane
	$("#add-lane").click(function(){
		
		var legs = [];
		
		$("#route tr").each(function(index, value){
			
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
			effective_date : contract_start_date,
			expiration_date : contract_end_date,
			legs : legs,
			value : $("#amount").val(),
			currency : $("#currency_code").val(),
			tariff : $("#tariff").val(),
			service : $("#service").val()
		};
		
		// save data
		$.post(site_url+"/admin/contract/savelane", data)
		.done(function(data){
			load_lanes();
		});
	});
	
	// add rule to lane
	$("#lanes").on("click", "button#save-rule", function(){
		// get parent table cell
		var $parent = $(this).parents("td");
		// get data from input fields
		var data = {
			lane_id : $(this).parents("tr").data("id"),
			charge_code : $(this).siblings("select#rule-charge_code").val(),
			currency : $(this).siblings("select#rule-currency").val(),
			amount : $(this).siblings("input#rule-amount").val(),
			effective : $(this).siblings("input#rule-effective-date").val(),
			expires : $(this).siblings("input#rule-expires-date").val(),
			notes : $(this).siblings("textarea#rule-notes").val()
		};
		$.post(site_url+"/admin/contract/savelanecharge", data)
		.done(function(data){
			// add rule to line
			console.log("td parent", $parent);
			$parent.removeClass('lane-rule-active').find("div.lane-rule-entry").parent().remove();
			var html = new EJS({url: base_url+'assets/templates/admin/contract/lane-rule.ejs'}).render({rule: data});
			// add the saved rule to the individual lane
			console.log("Table lane rules exists", $parent.children("table.lane-rules").length);
			if($parent.children("table.lane-rules").length > 0){
				$parent.children("table.lane-rules").append(html);
			}else{
				$parent.append('<table class="table offset1 span8 lane-rules">'+html+'</table>');
			}
		});
	});
	
	
	/**
	* Lane Charge Modal
	*/
	$("#add-lane-charge").click(function(){
		clearModal();
		// pop up the lane charge modal
		 show_charge_modal();
		
	});
	
	
	$("div#myModal").on("click", "button#add-charge-to-input", function(){
		var data = {
			amount : $("#rule-amount").val(),
			effective_date : $("#rule-effective-date").val(),
			expires_date : $("#rule-expires-date").val(),
			notes : $("#rule-notes").val(),
			currency_id : $("#rule-currency").val(),
			currency_desc : $("#rule-currency :selected").text().trim(),
			charge_code_id : $("#rule-charge_code").val(),
			charge_code_desc : $("#rule-charge_code :selected").text().trim()
		};
		var html = new EJS({url: base_url+'assets/templates/admin/contract/lane/input-charge.ejs'}).render(data);
		
		$("table#input-charges").append(html);
		
		$("div#myModal").modal("hide");
		
	});
	
	$("#input-charges").on("click", "button.delete-input-charge", function(){
		$(this).closest("tr").remove();
	});
	
	
	/*
	* Port Groups Pop up
	*/
	$("#use-port-groups").click(function(){
		show_port_group_dropdown();
		//show_add_port_groups();
	});
	
	$("div.container").on("click", "button#add-port-to-group", function(){
		var data = {
			port_name : $("#port-location").select2("data").name + ", "+$("#port-location").select2("data").country_code,
			port_id : $("#port-location").select2("val")
		};
		var html = new EJS({url: base_url+'assets/templates/admin/contract/lane/add-port-group-line-item.ejs'}).render(data);
		$("ul#port-grouping").append(html);
		$("ul#port-grouping > li").hover(
			function(){
				$(this).children("i.delete-from-port-group").show();
			},
			function(){
				$(this).children("i.delete-from-port-group").hide();
			}
		);
		
		$("ul#port-grouping > li > i.delete-from-port-group").click(function(){
			$(this).parent("li").remove();
		});
	});
	

	
	// load lanes on page load
	load_lanes();
	
});

/**
* clear input area
**/
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
		$("#lanes").hide();
		var html = "";
		for(var index = 0; index < data.length; index++){
			html += new EJS({url: base_url+'assets/templates/admin/contract/lane.ejs'}).render(data[index]);
			
		}
			
		
		$("#lanes").append(html);
		// enable popover
		$("a.desc").popover();
		
		$("#lanes").show("slow");
	});
		
}


/**
* show the modal to create new port groupings
*/
function show_add_port_groups() {
	var html = new EJS({url: base_url+'assets/templates/admin/contract/lane/add-port-group-modal.ejs'}).render();
	$("div#input-contract-line").before(html);
	create_location_port_dropdown("port-location");
	
	// attach click handlers
	$("button#add-port-group").click(function(){
		console.log("I got clicked");
		var port_ids = new Array();
		$("ul#port-grouping > li").each(function(index){
			port_ids.push($(this).data("port-id"));
		});
		
		var data = {
			port_ids: port_ids,
			name : $("input#group-name").val(),
			contract: contract_id
		}
		
		$.post(site_url+"/admin/contract/save_port_groups", data)
		.done(function(data){
			console.log(data);
		});
	});
}

/**
* show the modal associated with charges
*/
function show_charge_modal () {
	// retrieve list of charge codes
	$.get(site_url+"/services/list_of_charge_codes/"+carrier_id, function(results){
		var data = {
			charge_codes : results,
			currencies : currencies
		};
		var html = new EJS({url: base_url+'assets/templates/admin/contract/lane/lane-rule-modal.ejs'}).render(data);
		$("#myModal").append(html);
		// add date picker to date field
		$("#myModal").find("input#rule-effective-date").datepicker('setValue', convert_sqldate_to_date(contract_start_date));
		$("#myModal").find("input#rule-expires-date").datepicker('setValue', convert_sqldate_to_date(contract_end_date));
		// add what you see is what you get editor
		//$("#myModal").find('textarea').wysihtml5();
		// attach click handler to upload file
		$("#myModal").find("a.upload-file").click(function(){
			sendFiles($(this).siblings("input"));
		});
		$('#myModal').modal('show');
	});
}

function show_port_group_dropdown() {
	$.get(site_url+"/admin/contract/get_port_groups/"+contract_id, function(results){
		remove_location_port_dropdown();
		var html = new EJS({url: base_url+'assets/templates/admin/contract/lane/port-group-dropdown.ejs'}).render({data: results});
		$("input#location").before(html);
		
		$("ul#port-group-selector > li").click(function(){
			if($(this).data("id") == 1){
				
			}else{
				
			}
		});
	});
}

/**
* remove location drop down off the page
*/
function remove_location_port_dropdown(){
	$("#location").select2("destroy");
}

/**
* creates port drop down on location element
* used to select individual port as leg
*/
function create_location_port_dropdown (id) {
	var page_size = 10;
	// initialize selec2 on origin and destination
	$("#"+id).select2({
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

}



function clearModal(){
	$("#myModal").html("");
}



