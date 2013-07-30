
$(document).ready(function(){
	$("img.carrier-img").click(function(){
		var contract_id = $(this).data('contract-id');
		var pages = $(this).data("pages");
		
		contractPageViewer.init(contract_id, pages);
		register_overlay_handlers();
	});
	
	
	
	
	
});


var contractPageViewer = {
	init: function(contract_id, total_pages){
		contractPageViewer.contract_id = contract_id;
		contractPageViewer.total_pages = total_pages;	
		contractPageViewer.visible_page = 1;
		contractPageViewer.initHTML();
		// load 3 pages ahead
		contractPageViewer.loadAhead = 3;
		contractPageViewer.loadedPages = {};
		// load ahead extra pages
		contractPageViewer.initComplete = false;
		contractPageViewer.initLoadPages(null);
		
		contractPageViewer.loadArray = new Array();
		
	},
	initHTML: function(){
		// show html
		var html = new EJS({url: base_url+"assets/templates/contracts/contract-overlay.ejs"}).render();
		$("body").append(html);
		$("html, body").css("overflow","hidden");
		$("div#overlay-body").scrollTop(0);
		$("div#overlay-body > p").html("<span id='page_number'>1</span> of "+contractPageViewer.total_pages);
		contractPageViewer.initScrollHandler();	
		$("div#overlay").show();
	},
	initScrollHandler: function(){
		$('div#overlay-body-img').scroll(function () {
    		contractPageViewer.visible_page = $("img.cpage:in-viewport").data("page");
    		$("span#page_number").html(contractPageViewer.visible_page);
    		// check if next page exists on contract
    		// or if next page has already been downloaded
    		if(contractPageViewer.initComplete && 
    			(contractPageViewer.visible_page+contractPageViewer.loadAhead) <= contractPageViewer.total_pages){
				contractPageViewer.loadNextPage(contractPageViewer.visible_page+contractPageViewer.loadAhead);	
			}		
		});
	},
	initLoadPages: function(page_num){
		
		if(page_num == null){	
			contractPageViewer.loadPage(1, contractPageViewer.initLoadPages);
		}else if(page_num <= contractPageViewer.loadAhead){
			contractPageViewer.loadPage(page_num+1, contractPageViewer.initLoadPages);
		}else{
			contractPageViewer.initComplete = true;
		}
	},
	loadNextPage: function(page_num){
		// logic to forward load pdf
		contractPageViewer.loadPage(page_num);
		
	},
	loadPage: function(page_num, callback){
		/**
		 * check if the page has been loaded already
		 * or started loading already
		 */
		if(!(page_num in contractPageViewer.loadedPages)){
			contractPageViewer.loadedPages[page_num] = 'loading';
			$.get(site_url+"/contract/get_page/"+contractPageViewer.contract_id+"/"+page_num, function(data){
				if(data.success){
					var image_id =  "cpage"+page_num;
					var img = new Image();
					var url = data.page;
					img.src = url;
    				
    				$("div#overlay-body-img").append(
						"<img class='cpage' id='"+image_id+"' data-page="+page_num+" src='"+base_url+"/assets/img/ajax-loader.gif' />"
					).data({page: page_num});
    				
				    img.onload = function(){
				    $("img#"+image_id)
				  		.attr('src', url)
				  		.load(function(){
							contractPageViewer.loadedPages[page_num] = image_id;
							if(callback != undefined)
								callback(page_num);
						});
					}
					img = null;	
				}
			});
		}
	},
	setVisiblePage: function(page){
		contractPageViewer.visible_page = page;
	},
	goToNextPage: function(){
		// increment current page by 1
		contractPageViewer.visible_page += 1;
		$("span#page_number").html(contractPageViewer.visible_page);
		var img_id = "img#cpage"+contractPageViewer.visible_page;
		console.log("IMage id: ", img_id);
		$('div#overlay-body-img').animate({
        	 scrollTop: $(img_id).offset().top
     	}, 2000);
	},
	destroy: function(){
		$('div#overlay-body-img').unbind('scroll');
		$("html, body").css("overflow","auto");
		$("div#overlay-content").remove();
		$("div#overlay").hide();
		contractPageViewer.contract_id = null;
		contractPageViewer.total_pages = null;	
		contractPageViewer.visible_page = null;
	}
	
}





function register_overlay_handlers(){
	/*
	 * 
	 $("div#right-bar").click(function(e){
		
	});
	
	$("div#left-bar").click( function(e){
		var current_page = $("div#overlay-body-img").data("page");
		var contract_id = $("div#overlay-body-img").data("contract_id");
		change_page(contract_id, current_page+1);
	});
	*/
	
	$("div#close-overlay").click(function(e){
		contractPageViewer.destroy();
	});
}


