
function create_containers_list (appendto) {
	$.get(site_url+"/services/list_of_container_types/"+contract_id, function(results){
		var select = "<select>";
		for (var key in results)
		{
		   if (results.hasOwnProperty(key))
		   {
		     	var option = "<option value='"+results[key].id+"'>"+results[key].container_type+" - "+results[key].description+"</option>";
				select += option;
		   }
		}
		select += "</select>";
		$(select).appendTo(appendto);
	});
}
