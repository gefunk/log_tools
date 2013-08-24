
$(document).ready(function(){
	/**
	 * save a new container type to a contract
	 */
	$("#add-container").click(function(){
		var data = {type: $("#container_type").val(), text: $("#container_text").val(), type_text: $("#container_type :selected").text()};
		$.post(
			site_url+"/admin/contract/add_container", 
			{
				contract_id: contract_id,
				text: data.text,
				type: data.type	
			}
		).done(function(d){
			if(d){
				var html = new EJS({url: base_url+'assets/templates/admin/contract/container.ejs'}).render(data);
				$("#container-table").append(html);	
			}else{
				var alert = {
					type: "error", 
					header: "Error Adding Container", 
					text: "Sorry couldn't add container, error: ".d
				};
				$("#messages").append(
					new EJS({url: base_url+'assets/templates/alert.ejs'}).render(alert)
				);
			}
			
		});
	});
	
	/**
	 * delete container from contract
	 */
	$("button.delete-container").click(function(){
		var type = $(this).data('id');
		var row = $(this).parents("tr");
		$.post(
			site_url+"/admin/contract/delete_container", 
			{
				contract_id: contract_id,
				type: type		
			}
		).done(function(data){
			row.remove();
		});
		
	});
	
});
