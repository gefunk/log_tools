$(document).ready(function(){
	
	// attach to origin
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#origin-select',
		callback: function(obj){
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#origin-select").siblings("span.help-block").children("ul").html(html);
			$("#origin").val(obj.id);
			update_port_hit_count (obj.id);
		},
		formatter: function(port) {
			
 			return port.name+", "+port.country_name.toLowerCase();
		}
		
	});
	
	// attach to destination
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#destination-select',
		callback: function(obj){
			obj.base_url = base_url;
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#destination-select").siblings("span.help-block").children("ul").html(html);
			$("#destination").val(obj.id);
			update_port_hit_count (obj.id);
		},
		formatter: function(port) {
 			return port.name+", "+port.country_name.toLowerCase();
		}
	});
	
	// button to toggle inputs
	$("button.toggle-group").click(function(){
		if($(this).data('toggle') == 'off'){
			// show the Port Grouping Drop down
			
			// hide the port input
			$(this).siblings("input").hide();
			// show the drop down and reset it to the first value
			var $select = $(this).siblings("select");
			$select.val(0).show();
			// change the text and set the toggle to port grouping on
			$(this).text("Use Port").data('toggle','on');
			// set the hidden input origin or destination type to port grouping, depending on whats selected
			if($select.hasClass('origin')){
				$("#origin_type").val(1);
			}else{
				$("#destination_type").val(1);
			}
			
			
		}else{
			// show individual port selector
			
			// hide port group drop down
			var $select = $(this).siblings("select");
			$select.hide();
			// clear the individual port input
			var $input = $(this).siblings("input");
			$input.val("").show();
			// toggle to off to indicate port selector is showing
			$(this).text("Use Group").data('toggle','off');
			// set type of origin or destination to port, depending on which was selected
			if($select.hasClass('origin')){
				$("#origin_type").val(0);
			}else{
				$("#destination_type").val(0);
			}
		}
		
		
		
		// clear the help box of any values
		var $help_block = $(this).siblings("span.help-block");
		$help_block.html("<ul><li>"+$help_block.data('reset')+"</li></ul>");
		
	});
	
	// toggle group port list
	$("select.port-group-selector").change(function() {
		var $select = $(this);
		var port_group = $select.val();
		$.get(
			site_url+"/services/get_ports_for_group/"+port_group,
			function(data){
				var html = "";
				for(var i in data.results){
					var port = data.results[i];
					html += new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(port);
					
				}
				$select.siblings("span.help-block").children("ul").html(html)
			} 
		);
		// set the port group value to the origin or destination input
		if($select.hasClass('origin')){
			$("#origin").val($select.val());
		}else{
			$("#destination").val($select.val());
		}
	});
	
	
	// initalize date fields
	$("#from_date").datepicker('update');
	$("#to_date").datepicker('update');
	
});




