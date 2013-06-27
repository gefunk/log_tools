$(document).ready(function(){
	
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#origin-select',
		callback: function(obj){
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#origin-select").siblings("span.help-block").html(html);
		}
	});
	
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#destination-select',
		callback: function(obj){
			obj.base_url = base_url;
			var html = new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(obj);
			$("#destination-select").siblings("span.help-block").html(html);
		}
	});
	
	
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
});

