
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

});
