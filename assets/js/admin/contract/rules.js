$(document).ready(function(){

	// set up selected when clicked
	$("#rule-selector > li > a").click(function(){
		var verb = $(this).data("verb");
		var source = $(this).data("source");
		var html = $(this).text()+" "+verb;
		$("#rule-desc").html(html);
		
		var appendto = "#rule-input";
		var id = "#rule-entry";
		switch(source){
			case 1: 
				// source is cities
				create_cities_list(id, (verb == "are") ? true : false);
				break;
			case 2:
				// source is countries
				create_countries_list(id, (verb == "are") ? true : false);
				break;
			case 3:
				// source is ports
				create_ports_list(id, (verb == "are") ? true : false);
				break;
			case 4:
				// source is container types
				create_containers_list(appendto);
				break;
			default:
				break;
		}
	});
});