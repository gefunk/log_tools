$(document).ready(function(){
	
	// handle hover over element
	$("div.result-row").hover(
		function(){
			$(this).css("background-color", "#F5F5F5");
		},
		function(){
			$(this).css("background-color", "");
		}
	);
	
	
	// handle element click
	$("div.result-row").click(function(){
		if($(this).hasClass('well')){
			$(this).removeClass('well');
			$(this).children("div.result-detail").remove();
		}else{
			$(this).append('<div id="rd1" class="row-fluid result-detail"> <div class="span1"> <button type="button" class="btn"><i class="icon-filter"></i></button> </div> <div class="offset1"> <table class="table table-bordered"> <tr> <th>Description</th> <th>Price</th> <th>PPU</th> <th>Charge Applied by</th> <th>Effective Date</th> <th>Expirate Date</th> <th>Payment Due</th> </tr> <tr> <td>Agri Production GRI 03/01/13</td> <td>0.00</td> <td>0.00</td> <td>by container</td> <td>03/01/2013</td> <td>05/01/2013</td> <td>Prepaid</td> </tr> </table> </div> </div><!-- end child row -->');
			$(this).addClass("well");
		}
	});
	
	
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

