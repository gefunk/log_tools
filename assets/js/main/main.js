
function transform_port_to_datum(port){
	var name = port.name;
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
    	transport_icons.push(transport_icon_path+"train_"+icon_size+".png");
    }
    if(port.road == 1){
    	transport_icons.push(transport_icon_path+"road_"+icon_size+".png");
    }
    if(port.airport == 1){
    	transport_icons.push(transport_icon_path+"plane_"+icon_size+".png");
    }
    if(port.ocean == 1){
    	transport_icons.push(transport_icon_path+"ship_"+icon_size+".png");
    }
    
    return {
    	value: name,
    	tokens: tokens,
    	flag: base_url+"assets/img/flags_iso/24/"+port.country_code.toLowerCase()+".png",
    	port_code: port.country_code+port.port_code,
    	transport_icons: transport_icons
    };
}

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
    	flag: base_url+"assets/img/flags_iso/24/"+city.country_code.toLowerCase()+".png"
    };
}

var ejs = {};

ejs.compile = function(template){
	var compiled = new EJS({url: template});
	return compiled;
}

$(document).ready(function(){
	$('input#origin').typeahead([
		{
			name: 'ports',
			header: '<h4>Ports</h4>',
	  		remote: {
						url: site_url+"/services/search_ports/%QUERY",
						filter: function(parsedResponse){
							console.log("Inside Filter", parsedResponse);
							return parsedResponse.map(transform_port_to_datum);
						}
					},
	  		template: base_url+'assets/templates/port_suggestion.ejs',
	  		engine: ejs
		},
		{
	  		name: 'cities',
	  		header: '<h5>Cities</h5>',
	  		remote: {
						url: site_url+"/services/search_cities/%QUERY",
						filter: function(parsedResponse){
							console.log("Inside City response", parsedResponse);
							return parsedResponse.map(transform_city_to_datum);
						}
					},
	  		template: base_url+'assets/templates/city_suggestion.ejs',
	    	engine: ejs
		}
		
	]);
});
