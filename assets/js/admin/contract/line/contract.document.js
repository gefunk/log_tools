var contractDocument = {
	initialize: function(contract_id, customer_id, total_pages, callback){
		contractDocument.contract_id = contract_id;
		contractDocument.customer_id = customer_id;
		contractDocument.total_pages = total_pages+1;
		contractDocument.page_count = 0;
		contractDocument.callbacks = new Array();
		contractDocument.pageOnlyCallbacks = new Array();
	},
	addSubscriber: function(callback_func){
		contractDocument.callbacks.push(callback_func);
	},
	addPageSubscriber: function(callback_func){
		contractDocument.pageOnlyCallbacks.push(callback_func);
	},
	getPage: function(){
		if(contractDocument.page_count <= 0){
			contractDocument.page_count = 1;
		}
		$.get(
			site_url+"/contract/page/"+contractDocument.contract_id+'/'+contractDocument.customer_id+"/"+contractDocument.page_count,
			function(data){
				if(data.success){
					for(var i = 0; i < contractDocument.callbacks.length; i++){
						contractDocument.callbacks[i](data.page, contractDocument.page_count);
						contractDocument.pageOnlyCallbacks[i](contractDocument.page_count);
					}
				}
			}
		)	
	},
	getNextPage: function(){
		if(contractDocument.page_count < contractDocument.total_pages){
			contractDocument.page_count += 1;
			contractDocument.getPage();	
		}else{
			return false;
		}
	},
	getPreviousPage: function(){
		if(contractDocument.page_count > 1){
			contractDocument.page_count -= 1;
			contractDocument.getPage();
		}else{
			return false;
		}
	},
	goToPage: function(page){
		if(page > 0 && page < contractDocument.total_pages){
			contractDocument.page_count = page;
			contractDocument.getPage();
		}
	},
	destroy: function(){
		contractDocument.callbacks = null;
		contractDocument.pageOnlyCallbacks = null;
	}
	
}
