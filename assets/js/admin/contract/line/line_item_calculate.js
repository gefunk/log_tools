

$(document).ready(function(){
	// init the calculator
	LineItemCalculator.initialize($("input.container-value"), $("#origin"), $("#destination"), paintProductResults);
	
	$("#refresh-line-products").click(function(){
		LineItemCalculator.calculate();
	});
	
	$("#effective, #enddate").datepicker();
	
	$("#line-item-products tbody").on("click", "td.remove-product",function(){
		
		// remove from the product aray
		LineItemCalculator.product.splice($(this).data('index'), 1);
		// remove from the table
		$(this).parent().remove();
	});
	
	
});


/**
 * display results table
 */
function paintProductResults(){
	
	
	
	// clear out the table first
	$("#line-item-products tr:nth-child(n+2)").remove();
	
	/**
	 * display all the results
	 */
	if(LineItemCalculator.product.length > 0){
		for(var i in LineItemCalculator.product){
				var data_obj = LineItemCalculator.product[i];
				data_obj['index'] = i;
				var html = new EJS(
					{url: base_url+'assets/templates/admin/contract/line/product.ejs'}
				).render({data: data_obj});
			$("#line-item-products").append(
				html
			);
		}	
	}
	
	$("#results").show();
}



var LineItemCalculator = {
	initialize: function(container_inputs, origin_input, destination_input, resultCallback){
		LineItemCalculator.container_inputs = container_inputs;
		LineItemCalculator.origin_input = origin_input;
		LineItemCalculator.destination_input = destination_input;
		LineItemCalculator.callback = resultCallback;
		LineItemCalculator.reset();
		
	},
	/**
	 * reset the calculator to original state
	 */
	reset: function(){
		LineItemCalculator.container_values = new Array();
		LineItemCalculator.origin_ports = new Array();
		LineItemCalculator.destination_ports = new Array();
		LineItemCalculator.got_container_values = false;
		LineItemCalculator.got_origin_ports = false;
		LineItemCalculator.got_destination_ports = false;
		
		LineItemCalculator.product = new Array();
		LineItemCalculator.itemsRemoved = 0;
		
	},
	get_container_values: function(callback){
		LineItemCalculator.container_inputs.each(function(index) {
			var value = parseFloat($(this).val().replace(/^[^\d\.]*/, ''));
			if (!isNaN(value)) {
				LineItemCalculator.container_values.push({
					value : value,
					currency : $(this).siblings("select").val(),
					type : $(this).data("container-type")
				});
				
			}
		});
		callback('container');
	},
	get_origin_ports: function(callback){

		if(LineItemCalculator.origin_input.length > 0){
			if(LineItemCalculator.origin_input.data('type') == 'port'){
				/*
				 * get the information for the port 
				 * and add it to the origin_ports array
				 */
				var port_id = LineItemCalculator.origin_input.data('value');
				$.get(
					site_url+"/services/get_port_info_by_id/"+port_id,
					function(data){
						LineItemCalculator.origin_ports.push(data);
						callback('origin_ports');
					});
				
			}else if(LineItemCalculator.origin_input.data('type') == 'port_group'){
				var group_id = LineItemCalculator.origin_input.data('value');
				$.get(
					site_url+"/services/get_ports_for_group/"+group_id,
					function(data){
						for(var i in data.results){
							LineItemCalculator.origin_ports.push(data.results[i]);	
						}
						callback('origin_ports');
					});
			}
		}// end if origin_input.length > 0
		
	},
	get_destination_ports: function(callback){
		if(LineItemCalculator.destination_input.length > 0){
			if(LineItemCalculator.destination_input.data('type') == 'port'){
				var port_id = LineItemCalculator.destination_input.data('value');
				$.get(
					site_url+"/services/get_port_info_by_id/"+port_id,
					function(data){

						LineItemCalculator.destination_ports.push(data);
						callback('destination_ports');
					});
				
			}else if(LineItemCalculator.destination_input.data('type') == 'port_group'){
				var group_id = LineItemCalculator.destination_input.data('value');
				$.get(
					site_url+"/services/get_ports_for_group/"+group_id,
					function(data){
						for(var i in data.results){
							LineItemCalculator.destination_ports.push(data.results[i]);	
							
						}
						callback('destination_ports');
					});
			}
		}// end if origin_input.length > 0
	},
	
	calculate: function(){
		LineItemCalculator.reset();
		LineItemCalculator.get_container_values(LineItemCalculator.gotData);
		LineItemCalculator.get_origin_ports(LineItemCalculator.gotData);
		LineItemCalculator.get_destination_ports(LineItemCalculator.gotData);
	},
	gotData: function(data_type){
		switch(data_type){
			case 'container': 
				LineItemCalculator.got_container_values = true;
				break;
			case 'origin_ports':
				LineItemCalculator.got_origin_ports = true;
				break;
			case 'destination_ports':
				LineItemCalculator.got_destination_ports = true;
				break;
			default:
				break;
		}
		if(LineItemCalculator.got_container_values
			&& LineItemCalculator.got_origin_ports
			&& LineItemCalculator.got_destination_ports){
				// ready to calculate
				LineItemCalculator.multiply();
			}
		
	},
	multiply: function(){
		
		/*
		 * check if origin ports are not empty
		 * if they are not add them to the product
		 */ 
		if(LineItemCalculator.origin_ports.length > 0){
			for(var i in LineItemCalculator.origin_ports){
				var origin_port = LineItemCalculator.origin_ports[i];
				LineItemCalculator.product.push({origin: origin_port});
			}					
		}
		
		if(LineItemCalculator.destination_ports.length > 0){
			if(LineItemCalculator.product.length > 0){
				var local_product = new Array();
				/**
				 * origin has already been added so multiply by destination
				 */
				for(var factor in LineItemCalculator.product){
					// multiply by every destination port
					for(var i in LineItemCalculator.destination_ports){
						var destination_port = LineItemCalculator.destination_ports[i];
						local_product.push({origin: LineItemCalculator.product[factor].origin, destination: destination_port});
					} 
				}
				// replace with the multiplied result
				LineItemCalculator.product = local_product;
			}else{
				/* 
				 * there is nothing in the product array so
				 * add this as the first factor
				 */ 
				for(var i in LineItemCalculator.destination_ports){
					var destination_port = LineItemCalculator.destination_ports[i];
					LineItemCalculator.product.push({destination: destination_port});
				}	
			}
			
		}
		
		if(LineItemCalculator.container_values.length > 0){
			if(LineItemCalculator.product.length > 0){
				var local_product = new Array();
				/**
				 * product contains values so add 
				 * container_values factor
				 */
				for(var factor in LineItemCalculator.product){
					// multiply by every container_value
					for(var i in LineItemCalculator.container_values){
						var container_value = LineItemCalculator.container_values[i];
						var result_obj = {container: container_value};
						if(LineItemCalculator.product[factor].hasOwnProperty("origin")){
							result_obj['origin'] = LineItemCalculator.product[factor].origin;
						}
						if(LineItemCalculator.product[factor].hasOwnProperty("destination")){
							result_obj['destination'] = LineItemCalculator.product[factor].destination;
						}
						local_product.push(result_obj);
					} 
				}
				// replace with the multiplied result
				LineItemCalculator.product = local_product;
			}else{
				/* 
				 * there is nothing in the product array so
				 * add this as the first factor
				 */ 
				for(var i in LineItemCalculator.destination_ports){
					var destination_port = LineItemCalculator.destination_ports[i];
					LineItemCalculator.product.push({destination: destination_port});
				}	
			}
		}
		// callback function used to display results
		LineItemCalculator.callback();
	}// end multiply
	
	
		
};




