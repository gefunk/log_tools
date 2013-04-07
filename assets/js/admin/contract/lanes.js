
$(document).ready(function(){

		var appendto = null;
		var id = "#port-load";
		var labelstr = null;
		var multiple_rule = false;
		create_ports_list (labelstr, appendto, id, multiple_rule);
		
		var labelstr = null;
		var id = "#port-discharge";
		create_ports_list (labelstr, appendto, id, multiple_rule);
		
		create_containers_list("#container-types");
		
		$("#add-lane").click(function(){
			//savelane($contract_id, $from_port, $to_port, $value, $container_type, $cargo_type)
			var data = {
				contract_id: contract_id,
				from_port: $("#port-load").select2("val"),
				to_port: $("#port-discharge").select2("val"),
				value: $("#dollars").val()+"."+$("#cents").val(),
				charge_code: $("#charge_code").val(),
				cargo_type: "1"
			}
			console.log("Data", data);
			var posting = $.post(site_url+"/admin/contract/savelane", data);
			posting.done(console.log("Saved lane"));
		});

		
});
