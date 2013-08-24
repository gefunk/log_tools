
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
	$('#origin, #destination').typeahead(dropdown_datasets).on('typeahead:selected', function(event, datum) {
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
