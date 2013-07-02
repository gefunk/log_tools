$(document).ready(function(){
	
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#origin-select',
		callback: function(obj){
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#origin-select").siblings("span.help-block").children("ul").html(html);
		}
	});
	
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#destination-select',
		callback: function(obj){
			obj.base_url = base_url;
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#destination-select").siblings("span.help-block").children("ul").html(html);
		}
	});
	
	// button to toggle inputs
	$("button.toggle-group").click(function(){
		if($(this).data('toggle') == 'off'){
			$(this).siblings("input").hide();
			$(this).siblings("select").show();
			$(this).text("Use Port").data('toggle','on');	
		}else{
			$(this).siblings("select").hide();
			$(this).siblings("input").show();
			$(this).text("Use Group").data('toggle','off');
		}
		
	});
	
	// toggle group port list
	$("select.port-group-selector").change(function() {
		var $select = $(this);
		var port_group = $select.val();
		$.get(
			site_url+"/services/get_ports_for_group/"+port_group+"/"+contract_id,
			function(data){
				var html = "";
				for(var i in data.results){
					var port = data.results[i];
					html += new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(port);
					
				}
				$select.siblings("span.help-block").children("ul").html(html)
			} 
		);	
	});
	
	
});

