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



var dropdown_datasets = [{
	name : 'ports',
	remote : {
		url : site_url + "/services/search_ports/%QUERY",
		filter : function(parsedResponse) {
			return parsedResponse.map(transform_port_to_datum);
		}
	},
	template : base_url + 'assets/templates/port_suggestion.ejs',
	engine : ejs,
	limit : 5
}];







$(document).ready(function(){
	
	
		/**
	 * attach autocomplete functionality to
	 * inputs
	 */
	$('input#origin-select, input#destination-select')
	.typeahead(dropdown_datasets)
	.on('typeahead:selected', function(event, datum) {
		//$("input#origin").data("type", datum.type).data("value", datum.id);
		$(this).data("type", datum.type).data("value", datum.id);
		if(datum.type == 'port'){
			$.post(site_url+'/services/increment_port_hit_count',{port_id: datum.id});
		}
	});

	// button to toggle inputs
	$("button.toggle-group").click(function(){
		if($(this).data('toggle') == 'off'){
			// show the Port Grouping Drop down
			
			// hide the port input
			$(this).siblings("input").hide();
			// show the drop down and reset it to the first value
			var $select = $(this).siblings("select");
			$select.val(0).show();
			// change the text and set the toggle to port grouping on
			$(this).text("Use Port").data('toggle','on');
			// set the hidden input origin or destination type to port grouping, depending on whats selected
			if($select.hasClass('origin')){
				$("#origin_type").val(1);
			}else{
				$("#destination_type").val(1);
			}
			
			
		}else{
			// show individual port selector
			
			// hide port group drop down
			var $select = $(this).siblings("select");
			$select.hide();
			// clear the individual port input
			var $input = $(this).siblings("input");
			$input.val("").show();
			// toggle to off to indicate port selector is showing
			$(this).text("Use Group").data('toggle','off');
			// set type of origin or destination to port, depending on which was selected
			if($select.hasClass('origin')){
				$("#origin_type").val(0);
			}else{
				$("#destination_type").val(0);
			}
		}
		
		
		
		// clear the help box of any values
		var $help_block = $(this).siblings("span.help-block");
		$help_block.html("<ul><li>"+$help_block.data('reset')+"</li></ul>");
		
	});
	
	// toggle group port list
	$("select.port-group-selector").change(function() {
		var $select = $(this);
		var port_group = $select.val();
		$.get(
			site_url+"/services/get_ports_for_group/"+port_group,
			function(data){
				var html = "";
				for(var i in data.results){
					var port = data.results[i];
					html += new EJS({url: base_url+'assets/templates/admin/contract/line/help_block.ejs'}).render(port);
					
				}
				$select.siblings("span.help-block").children("ul").html(html)
			} 
		);
		// set the port group value to the origin or destination input
		if($select.hasClass('origin')){
			$("#origin").val($select.val());
		}else{
			$("#destination").val($select.val());
		}
	});
	
	
	// initalize date fields
	$("#from_date").datepicker('update');
	$("#to_date").datepicker('update');
	
	
	
	/*
	 * check if a file has been uploaded for the contract
	 * if it ias then show the the file upload section
	 */ 	
	$.get(
  		site_url+"/admin/contract/upload_status/"+contract_id,
  		function(data){
  			if(data && data[0] != null){
  				// there is a document uploaded for this contract
  				$("div#contracts-physical").show();
				// initialize contract page(s)
				$("img#contract-page").hide();
				// initialize the document
				contractDocument.initialize(contract_id, customer_id, 20);
				// initialize the highlighter
				contractHighlighter.initialize($("img#contract-page"), contract_id);
				// add subscribers for broadcast
				contractDocument.addSubscriber(update_contract_page);
				contractDocument.addPageSubscriber(contractHighlighter.getHighlightsForPage);
				
				contractDocument.getNextPage();
				// initialize contract highlighter
 
  			}
  		}
  	);
	
		
	
	$("a#contract-page-go-right").click(function(){
		page_change_transition();
		contractDocument.getNextPage();
	});
	
	$("a#contract-page-go-left").click(function(){
  		page_change_transition();
		contractDocument.getPreviousPage();
	});
	
	$("input#contract-page-number").change(function(){
		console.log("this should change", $(this).val());
		page_change_transition();
		contractDocument.goToPage($(this).val());
	});
	
	
	
	// handle highlighter delete
	$("div#contract-pages").on("mouseover", "div.highlighter", function(e){
		$(this).children("i").show();
	}).on("mouseleave", "div.highlighter", function(e){
		$(this).children("i").hide();
	}).on("click", "div.highlighter", function(e){
		// stop click from bubbling up
		e.preventDefault();
		if(e.target.nodeName.toLowerCase() == 'i'){
			contractHighlighter.deleteHighlight(this.id);
		}		

	});
	
});


function page_change_transition () {
 	$("img#contract-page").addClass('fade');
  	$("div#contract-page-loading").show();	
  	$("input#contract-page-number").prop('disabled', true);
}


function update_contract_page (url, page) {
	//console.log("update contract -page called");
	var curImg = new Image();

    curImg.src = url;
    
    curImg.onload = function(){
    	$("img#contract-page")
  			.attr('src', url)
  			.load(function(){
  				$(this).show('fast', function(){
  					$("img#contract-page").removeClass('fade');
  					$("div#contract-page-loading").hide();
  					$("input#contract-page-number").val(page).prop('disabled', false);
  					
  					//contractHighlighter.getHighlightsForPage(page);
  				});
  			});    	
    }
    
  
  
  
}
