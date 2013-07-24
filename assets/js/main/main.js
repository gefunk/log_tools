

/**
 * transformation function
 * from port result object 
 * to datum for twitter typeahead
 * @param {Object} port
 */
function transform_port_to_datum(port){
	var name =  port.name;
	var tokens = new Array();
	tokens.push(port.name);
	if(port.state && /\S/.test(port.state)){
		name += ", "+port.state;
		tokens.push(port.state);
	}
	name += ", "+toTitleCase(port.country_name);
	tokens.push(port.country_name);
	tokens.push(port.country_code);
    
    var transport_icons = new Array();
    var transport_icon_path = base_url+"assets/img/transport_icons/";
    var icon_size = 24;
    if(port.rail == 1){
    	transport_icons.push("icon-train");
    }
    if(port.road == 1){
    	transport_icons.push("icon-road");
    }
    if(port.airport == 1){
    	transport_icons.push("icon-plane");
    }
    if(port.ocean == 1){
    	transport_icons.push("icon-boat");
    }
    
    return {
    	value:  name,
    	tokens: tokens,
    	id: port.id,
    	type: "port",
    	flag: base_url+"assets/img/flags_iso/24/"+port.country_code.toLowerCase()+".png",
    	port_code: port.country_code+port.port_code,
    	transport_icons: transport_icons
    };
}


/**
 * transformation function from city
 * result object to port object
 * @param {Object} city
 */
function transform_city_to_datum (city) {
  	var name = city.city_name;
	var tokens = new Array();
	tokens.push(city.city_name);
	if(city.state && /\S/.test(city.state)){
		name += ", "+city.state;
		tokens.push(city.state);
	}
	name += ", "+toTitleCase(city.country_name);
	tokens.push(city.country_name);
	tokens.push(city.country_code);
	
	return {
    	value: name,
    	tokens: tokens,
    	id: city.id,
    	type: "city",
    	flag: base_url+"assets/img/flags_iso/24/"+city.country_code.toLowerCase()+".png"
    };
}

var dropdown_datasets = [
	{
			name: 'ports',
			header: '<h5>Ports</h5>',
	  		remote: {
						url: site_url+"/services/search_ports/%QUERY",
						filter: function(parsedResponse){
							
							return parsedResponse.map(transform_port_to_datum);
						}
					},
	  		template: base_url+'assets/templates/port_suggestion.ejs',
	  		engine: ejs,
	  		limit: 3
		},
		{
	  		name: 'cities',
	  		header: '<h5>Cities</h5>',
	  		remote: {
						url: site_url+"/services/search_cities/%QUERY",
						filter: function(parsedResponse){
							
							return parsedResponse.map(transform_city_to_datum);
						}
					},
	  		template: base_url+'assets/templates/city_suggestion.ejs',
	    	engine: ejs
		}
];


$(document).ready(function(){
	$('input#origin').typeahead(dropdown_datasets)
	.on('typeahead:selected', function(event, datum) {
        $("input#origin").data("type", datum.type).data("value", datum.id);
		
		console.log("Appended", $("input#origin").siblings("span:not(.tt-dropdown-menu)"));  
     });
 
	$('input#destination').typeahead(dropdown_datasets)
	.on('typeahead:selected', function(event, datum) {
        $("input#destination").data("type", datum.type).data("value", datum.id);
     });
     
     //filter clicks
     $("div.filter").click(function(e){
     	var offset = $(this).position();
     	var top = offset.top+$(this).outerHeight()+5;
     	var left = offset.left;
     	var dropdown_id = "#"+$(this).data("filter-dropdown");
     	if($(dropdown_id).data("toggle") == 'off'){
     		e.stopPropagation();
     		$("div.filter-dropdown").data('toggle', 'off').css("display", "none");
     		$(dropdown_id).css({top: top,left: left}).show().data('toggle','on');
     	}
     });
     
     $("div.filter-dropdown").click(function(e){
     	e.stopPropagation();
     });
     
     
     $("div.container-fluid").click(function(e){
     	console.log("Container fluid click event");
     	// only fire event if the user isnt clicking on a filter
	     $("div.filter-dropdown").trigger({
			type: "closefilters"
		});
     });
     
     
     
     $("div.filter-dropdown").on("closefilters", function(){
     	console.log("event close filter fired");
     	$(this).data('toggle', 'off').css("display", "none");
     });
     
});
