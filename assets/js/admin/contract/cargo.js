
$(document).ready(function(){
	$.get(
		site_url+"/admin/contract/get_cargo_types/"+contract_id,
		function(data){
			for(var i=0; i < data.length; i++){
				var obj = {cargo_type: data[i]};
				var html = new EJS({url: base_url+'assets/templates/admin/contract/cargo-type.ejs'}).render(obj);
				$("#cargo-types").append(html);			
			}
			
		}
	);
	
	/**
	 * Add new cargo type
	 */
	$("#add-cargo-type").click(function(){
		$.post(
			site_url+"/admin/contract/add_cargo_type",
			{cargo: $("#cargo_type").val(), contract_id: contract_id}
		).done(function(data){
			if(data){
				var obj = {cargo_type: $("#cargo_type").val()};
				var html = new EJS({url: base_url+'assets/templates/admin/contract/cargo-type.ejs'}).render(obj);
				$("#cargo-types").append(html);
				$("#cargo_type").val("");	
			}
			
		});
	});
	
	
	/**
	 * remove cargo type
	 */
	$("#cargo-types").on("click", "span.delete-cargo-type",function(){
		var $cargo_type = ($(this).parent());
		//console.log("Cargo Type", $cargo_type);
		$.post(
			site_url+"/admin/contract/remove_cargo_type",
			{cargo: ($cargo_type.text()).trim(), contract_id: contract_id}
		).done(function(data){
			if(data){
				$cargo_type.remove();	
			}
		});
	});
	
});
