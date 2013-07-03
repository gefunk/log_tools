function attach_autocomplete_handler (options) {
	
	
  	$(options.input_id).typeahead({
	  	source: function(query, process){
	  		labels = [];
	    	lookup = {};
	    	
	    	query_data = {
	    		query: query,
	    		page_size: options.page_size
	    	};
	    	
	    	$.get(
	    		options.source,
	    		query_data
	    	).done(
	    		function(data){
	    			$.each(data['results'], function(i, result){
	    				//console.log("Result", result.name);
	    				item = options.formatter(result);
	    				lookup[item] = result;
	    				labels.push(item);
	    			});
	    			//console.log("Labels: "+labels);
	    			process(labels);
	    		}
	    		
	    	);

	  	},
	  	matcher: function (item) {
        	return true;
		},
		items: options.page_size,
		updater: function(item){
			if(options.callback !== undefined){
				options.callback(lookup[item]);	
			}
			return item;
		}
  });  
}

