$(document).ready(function(){
	
	// load ports
	$.get(
		site_url+'/services/get_port_groups/'+contract_id,
		function(data){
			for(var i in data){
				var html = new EJS({url: base_url+'assets/templates/admin/contract/port_options.ejs'}).render(data[i]);
				$("#port-groups").append(html);
			}
		}
	);
	
	$("#add-port-group").click(function(){
		$.post(
			site_url+'/admin/contract/save_new_port_group',
			{
				name: $("#new-port-group").val(), 
				contract: contract_id
			}
		).done(function(data){
				var html = new EJS({url: base_url+'assets/templates/admin/contract/port_options.ejs'}).render(data);
				$("#port-groups").append(html);
				$("#new-port-group").val("");
				successfully_saved();
		});
	});
	
	
	$("#port-groups").change(function(){
		$("ul#ports-list li").remove();
		$.get(
			site_url+"/services/get_ports_for_group/"+$(this).val(),
			function(data){
				var html = " ";
				for(var i in data.results){
					var port = data.results[i];
					html += new EJS({url: base_url+'assets/templates/admin/contract/port-admin-li.ejs'}).render(port);
					
				}
				$("ul#ports-list").html(html);
			} 
		);
	});
	
	
	// attach to port - input
	attach_autocomplete_handler ({
		source:  site_url+"/services/ports_type_ahead",
		page_size: 10,
		input_id: '#port-input',
		callback: function(obj){
			$.post(
				site_url+"/admin/contract/save_new_port_to_group",
				{
					port_id: obj.id,
					group_id: $("#port-groups").val()
				} 
			);
			var html = new EJS({url: base_url+'assets/templates/admin/contract/port-admin-li.ejs'}).render(obj);
			$("ul#ports-list").append(html);
			update_port_hit_count (obj.id);
		},
		formatter: function(port) {
			var port_name = port.name;
			if(port.state)
				port_name += ", "+port.state;
			port_name += ", "+port.country_code;
			return port_name;
 			
		}
		
	});
	
	
	
	// handle delete 
	$("ul#ports-list").on("click", "span.delete", function(){
		var $li = $(this).parent("li");
		$.post(
			site_url+"/admin/contract/delete_port_from_group",
			{group_id: $("#port-groups").val(), port_id: $li.data("port-id")}
		).done(function(){
			$li.remove();	
		});
		
	});
	
	
});


function successfully_saved () {
	var data = {type: 'success', header: "Saved Port Group", text: "Successfully saved port group, will appear in drop down, you can close this any time"};
 	var html = new EJS({url: base_url+'assets/templates/alert.ejs'}).render(data);
 	setTimeout(5000, $('.alert').alert('close'));
 	$("#messages > div").html(html);
}
