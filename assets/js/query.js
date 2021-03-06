$(document).ready(function(){
	
	// search button click
	$("button#search").click(function(){
		search_lanes();
	});
	
	
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
				$(this).css("background-color", "#F5FAFC");
			},
		mouseleave: function(){
				$(this).css("background-color", "");
			}
	}, "div.result-row");
	
	
	/** search ports and city section **/
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
	
	/**
	* handle click on port
	*/
	$("div.container-fluid").on("click", "a.origin-port", function(e){
		console.log("clicked");
		e.stopPropagation();
	
		$(this).popover({
			html: true,
			placement: "top",
			content: get_origin_info,
			delay: { show: 500, hide: 100 },
			trigger: 'manual',
			container: $(this)
		});
		$(this).popover('show');
	});
	

	create_start_to_date_fields ("from_date", "to_date", {disable_before_today: true}) ;
	/** date picker for start and end date *
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	var start_date = $("#from_date").datepicker({
		onRender: function(date) {
			return date.valueOf() < now.valueOf() ? 'disabled' : '';
		}
	}).on('changeDate', function(ev) {
		if (ev.date.valueOf() > end_date.date.valueOf()) {
			var newDate = new Date(ev.date)
			newDate.setDate(newDate.getDate() + 1);
			end_date.setValue(newDate);
		}
		start_date.hide();
		$('#to_date')[0].focus();
	}).data('datepicker');
	
	var end_date = $('#to_date').datepicker({
			onRender: function(date) {
				return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
			}
		}).on('changeDate', function(ev) {
			end_date.hide();
		}).data('datepicker');	
		*/
	
});


function get_origin_info(element) {
	var service = $(element).data("service"); 
	var distance = $(element).data("origin-distance"); 
	var origin_city = $(element).siblings("span.origin-city").text();
	var content = "<p>"+service+"</p><p>"+ distance + " from " + origin_city + "</p>";
	return content;
}

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
	
	data.origin_name = origin_name;
	data.dest_name = dest_name;
	data.base_url = base_url;
	
	console.log(data);

	
	var html = new EJS({url: base_url+'assets/templates/query.ejs'}).render(data);
	
	$("div.container-fluid").append(html);
	
	$('a.shipcontainer, a.cargo').popover();
	
	

	
	/*
	$('a.origin-city').popover({
		html: data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<%= popover_text %>"
	});
	*/
}
