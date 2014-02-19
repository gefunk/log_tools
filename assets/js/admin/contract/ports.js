



var dropdown_datasets = [{
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
	
	// load ports
	$.get(
		site_url+'/services/get_port_groups/'+contract_id,
		function(data){
			for(var i in data.port_groups){
				var template_data = {id: data.port_groups[i].name, name: data.port_groups[i].name};
				var html = new EJS({url: base_url+'assets/templates/admin/contract/port_options.ejs'}).render(template_data);
				$("select#port-groups").append(html);
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
	
	
	$("select#port-groups").change(function(){
		$("ul#ports-list li").remove();
		$.post(
			site_url+"/services/get_ports_for_group",
			{contract_id: contract_id, group_id: $(this).val()},
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
	
	
	$('input#port-input').typeahead(dropdown_datasets).on('typeahead:selected', function(event, datum) {
		//$("input#origin").data("type", datum.type).data("value", datum.id);
		$(this).data("type", datum.type).data("value", datum.id);
		if(datum.type == 'port'){
			$.post(site_url+'/services/increment_port_hit_count',{port_id: datum.id});
		}
	});
	
	$("button#add-port-to-group").click(function(){
		var port_id = $("input#port-input").data("value");
		if(port_id != null && port_id.length > 0){
			$.post(site_url+"/admin/contract/save_new_port_to_group", {contract_id: contract_id, port_id: port_id, group_id: $("#port-groups").val()})
			.done(function(data){
				var html = new EJS({url: base_url+'assets/templates/admin/contract/port-admin-li.ejs'}).render(data[0]);
				$("ul#ports-list").append(html);
			});
		}
	});
	
	
	// handle delete 
	$("ul#ports-list").on("click", "span.delete", function(){
		var $li = $(this).parent("li");
		$.post(
			site_url+"/admin/contract/delete_port_from_group",
			{contract_id: contract_id, group_id: $("#port-groups").val(), port_id: $li.data("port-id")}
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
