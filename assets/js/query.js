$(document).ready(function(){
	
	// search button click
	$("button#search").click(function(){
		search_lanes();
	});
	
	
	/* handle hover over element
	$("div.result-row").hover(
		function(){
			$(this).css("background-color", "#F5F5F5");
		},
		function(){
			$(this).css("background-color", "");
		}
	);*/
	
	$("body").on({
		click: function(){
			if($(this).hasClass('well')){
				$(this).removeClass('well');
				$(this).children("div.result-detail").remove();
			}else{
				$(this).append('<div id="rd1" class="row-fluid result-detail"> <div class="span1"> <button type="button" class="btn"><i class="icon-filter"></i></button> </div> <div class="offset1"> <table class="table table-bordered"> <tr> <th>Description</th> <th>Price</th> <th>PPU</th> <th>Charge Applied by</th> <th>Effective Date</th> <th>Expirate Date</th> <th>Payment Due</th> </tr> <tr> <td>Agri Production GRI 03/01/13</td> <td>0.00</td> <td>0.00</td> <td>by container</td> <td>03/01/2013</td> <td>05/01/2013</td> <td>Prepaid</td> </tr> </table> </div> </div><!-- end child row -->');
				$(this).addClass("well");
			}
		},
		mouseenter: function(){
				$(this).css("background-color", "#F5F5F5");
			},
		mouseleave: function(){
				$(this).css("background-color", "");
			}
	}, "div.result-row");
	
	// handle element click
	/*$("div.result-row").click(function(){
		if($(this).hasClass('well')){
			$(this).removeClass('well');
			$(this).children("div.result-detail").remove();
		}else{
			$(this).append('<div id="rd1" class="row-fluid result-detail"> <div class="span1"> <button type="button" class="btn"><i class="icon-filter"></i></button> </div> <div class="offset1"> <table class="table table-bordered"> <tr> <th>Description</th> <th>Price</th> <th>PPU</th> <th>Charge Applied by</th> <th>Effective Date</th> <th>Expirate Date</th> <th>Payment Due</th> </tr> <tr> <td>Agri Production GRI 03/01/13</td> <td>0.00</td> <td>0.00</td> <td>by container</td> <td>03/01/2013</td> <td>05/01/2013</td> <td>Prepaid</td> </tr> </table> </div> </div><!-- end child row -->');
			$(this).addClass("well");
		}
	});*/
	
	
	// handle filter button click
	$("button#filter").click(function(){
		if($("div#filter-input").is(":visible")){
			$("div#filter-input").hide("500");						
		}else{
			$("div#filter-input").show("500");			
		}

	});
	
	
	var page_size = 10;
	// initialize selec2 on origin and destination
	$("#origin, #destination").select2({
		placeholder: "Search for a city",
		multiple: false,
		minimumInputLength: 4,
		ajax: {
		url: site_url+"/services/list_of_cities",
		dataType: 'json',
		quietMillis: 300,
		data: function (term, page) { // page is the one-based page number tracked by Select2
			return {
				query: term, //search term
				page_size: page_size, // page size
				page: page // page number
			};
		},
		results: function (data, page) {
			var more = (page * page_size) < data.total;
			// notice we return the value of more so Select2 knows if more results can be loaded
			return {results: data.results, more: more};
			}
		},
		formatResult: format_city_result, // omitted for brevity, see the source of this page
		formatSelection: format_city, // omitted for brevity, see the source of this page
		dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
		escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
	});
	// initialize seelct2 on ports
	$("#limit-origin, #limit-destination").select2({
		multiple: false,
		minimumInputLength: 4,
		ajax: {
		url: site_url+"/services/list_of_ports",
		dataType: 'json',
		quietMillis: 300,
		data: function (term, page) { // page is the one-based page number tracked by Select2
			return {
				query: term, //search term
				page_size: page_size, // page size
				page: page // page number
			};
		},
		results: function (data, page) {
			var more = (page * page_size) < data.total;
			// notice we return the value of more so Select2 knows if more results can be loaded
			return {results: data.results, more: more};
			}
		},
		formatResult: format_port_result, // omitted for brevity, see the source of this page
		formatSelection: format_port, // omitted for brevity, see the source of this page
		dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
		escapeMarkup: function (m) { return m; } // we do not want to escape markup since we are displaying html in results
	});
	
});


function search_lanes () {
	
	// remove all the results first
	$("div.result-row").remove();
	
	var origin_city = $("#origin").select2("val");
	var dest_city = $("#destination").select2("val");
	// will get this through the url
	var customer_id = 9;
	
	$.get(site_url+"/main/searchlanes/"+origin_city+"/"+dest_city+"/"+customer_id, parse_search_response);
}

function parse_search_response (data) {
	console.log(data);
	
	var origin = $("#origin").select2("data");
	var origin_name = origin.city_name;
	if(origin.state)
		origin_name += ", "+ origin.state;
	origin_name += ", "+origin.country_code;
		
	var destination = $("#destination").select2("data");
	var dest_name = destination.city_name;
	if(destination.state)
		dest_name += ", "+destination.state;
	dest_name += ", "+destination.country_code;
	
	var lanes = data.lane_detail;
	for(lane in lanes){
		var html = '<div class="result-row">';
		html += '<div class="row-fluid">';
		html += format_carrier(lanes[lane]);
		html += add_rate_body()+format_rate_heading(origin_name, lanes[lane].legs, dest_name);
		html += format_rate_subtext(lanes[lane]);
		html += close_rate_body();
		html += format_price(lanes[lane]);
		html += '</div><!-- end parent row -->';
		html += '</div>';
		$("div.container-fluid").append(html);
	}
	
	
}

function format_carrier (lane) {
	var html = '<div class="span1 carrier-logo">';
	html += '<img src="'+base_url+'assets/img/carriers/'+lane.carrier_image+'" width="64px" height="64px">';
	html += '</div>';
	return html;
}

function add_rate_body () {
	return '<div class="span9 rate-body">';
}

function close_rate_body (argument) {
	return '</div><!-- end class rate-body -->';
}

function format_rate_heading (origin, legs, destination) {
		var html = '<div class="rate-heading">';
		html += '<span id="origin-city" class="via-city">'+origin+'</span>&rarr;';
		var leg_html = '';
		for(leg in legs){
			var leg_name = legs[leg].location;
			
			if(legs[leg].state){
				leg_name += ', '+legs[leg].state;
			}
			leg_name += ', '+legs[leg].country_code;
			leg_name += ' ('+legs[leg].transport_type+')';
			console.log("leg name", leg_name);
			var class_name = "via-city";
			if(legs[leg].leg_type == "origin" || legs[leg].leg_type == "destination" ){
				class_name = "primary-city";
			}
			if(leg_html.length > 0){
				leg_html += "&rarr;";
			}
			leg_html += '<span class="'+class_name+'">'+leg_name+'</span>';
		}
		html += leg_html+'&rarr;<span id="destination-city"  class="via-city">'+destination+'</span>';
		html += '</div>';
		return html;
}

function format_rate_subtext (lane) {
	var html = '<div class="rate-subtext">';
	html +=	'<div class="span3">';
	html +=	'<span class="info">date:</span>'+lane.effective_date;
	html += '</div>';
	html += '<div class="span3">';
	html += '<span class="info">commodity:</span>'+lane.cargo;
	html += '</div>';
	html +=	'<div class="span3">';
	html += '<span class="info">container:</span>'+lane.container;
	html += '</div>';
	html += '</div>';
	return html;
}

function format_price (lane) {
	var html = '<div class="span2 rate-price">';
	html +=	'<div id="sell-rate"><span class="info">sell:</span>$1700</div>';
	html +=	'<div id="buy-rate"><span class="info">base:</span>'+lane.currency_symbol + lane.value+" "+lane.currency+'</div>';
	html += '<div id="margin">';
	html += '<span class="info">margin:</span>$100</div>';
	html += '</div>';
	html += '</div>';
	return html;
}
