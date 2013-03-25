
$(document).ready(function(){

		
		var appendto = "#port-form";
		var id = "#port-load";
		var labelstr = "Select an Origin Port";
		var multiple_rule = false;
		create_ports_list (labelstr, appendto, id, multiple_rule);
		
		var labelstr = "Select a DestinationPort";
		var id = "#port-discharge";
		create_ports_list (labelstr, appendto, id, multiple_rule);

});
