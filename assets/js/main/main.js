/**
 * transformation function
 * from port result object
 * to datum for twitter typeahead
 * @param {Object} port
 */
function transform_port_to_datum(port) {
	var name = port.name;
	var tokens = new Array();
	tokens.push(port.name);
	if (port.state && /\S/.test(port.state)) {
		name += ", " + port.state;
		tokens.push(port.state);
	}
	name += ", " + toTitleCase(port.country_name);
	tokens.push(port.country_name);
	tokens.push(port.country_code);

	var transport_icons = new Array();
	var transport_icon_path = base_url + "assets/img/transport_icons/";
	var icon_size = 24;
	if (port.rail == 1) {
		transport_icons.push("icon-train");
	}
	if (port.road == 1) {
		transport_icons.push("icon-truck");
	}
	if (port.airport == 1) {
		transport_icons.push("icon-plane");
	}
	if (port.ocean == 1) {
		transport_icons.push("icon-anchor");
	}

	return {
		value : name,
		tokens : tokens,
		id : port.id,
		type : "port",
		flag : port.country_code.toLowerCase(),
		port_code : port.country_code + port.port_code,
		transport_icons : transport_icons
	};
}

/**
 * transformation function from city
 * result object to port object
 * @param {Object} city
 */
function transform_city_to_datum(city) {
	var name = city.city_name;
	var tokens = new Array();
	tokens.push(city.city_name);
	if (city.state && /\S/.test(city.state)) {
		name += ", " + city.state;
		tokens.push(city.state);
	}
	name += ", " + toTitleCase(city.country_name);
	tokens.push(city.country_name);
	tokens.push(city.country_code);

	return {
		value : name,
		tokens : tokens,
		id : city.id,
		type : "city",
		flag : city.country_code.toLowerCase()
	};
}

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
}, {
	name : 'cities',
	header : '<h5>Cities</h5>',
	remote : {
		url : site_url + "/services/search_cities/%QUERY",
		filter : function(parsedResponse) {
			return parsedResponse.map(transform_city_to_datum);
		}
	},
	template : base_url + 'assets/templates/city_suggestion.ejs',
	engine : ejs,
	limit : 3
}];

$(document).ready(function() {

	/**
	 * attach autocomplete functionality to
	 * inputs
	 */
	$('input#origin, input#destination').typeahead(dropdown_datasets).on('typeahead:selected', function(event, datum) {
		//$("input#origin").data("type", datum.type).data("value", datum.id);
		$(this).data("type", datum.type).data("value", datum.id);
		if(datum.type == 'port'){
			$.post(site_url+'/services/increment_port_hit_count',{port_id: datum.id});
		}
	});

	
	if(isMobileDevice()){
		$("input#ship_date").attr('readonly', "true");	
	}

	$("div#ship-date").datepicker().on('changeDate', function (ev) {
		$(this).children("span").text($(this).data('date'));
    	$(this).datepicker('hide');
	});

	/*
	 * Handle clicks on Filter button
	 */
	$("div.filter").click(function(e) {
		var offset = $(this).position();
		var top = offset.top + $(this).outerHeight() + 5;
		var left = offset.left;
		var dropdown_id = "#" + $(this).data("filter-dropdown");
		if ($(dropdown_id).data("toggle") == 'off') {
			e.stopPropagation();
			$("div.filter-dropdown").data('toggle', 'off').css("display", "none");
			$(dropdown_id).css({
				top : top,
				left : left
			}).show().data('toggle', 'on');
		}
	});

	// keep dropdown open when selecting checkboxes, stop propagation
	$("div.filter-dropdown").click(function(e) {
		e.stopPropagation();
	});

	// capture the events at the container level so any click turns off
	// the filter
	$("div.container-fluid").click(function(e) {
		//console.log("Container fluid click event");
		// only fire event if the user isnt clicking on a filter
		$("div.filter-dropdown").trigger({
			type : "closefilters"
		});
	});

	// listen for close filter message and close itself
	$("div.filter-dropdown").on("closefilters", function() {
		//console.log("event close filter fired");
		$(this).data('toggle', 'off').css("display", "none");
	});

});
