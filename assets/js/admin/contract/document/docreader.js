
/**
 * represents a Document stored on Amazon
 */
function StoredDocument(id, url, pages){
	/* 
	* the url supplied should be 'url/<page_num>', 
	* the result of this should be json {success:true, page:<url to page>}
	* 
	*/
	this.id = id;
	this.url = url;
}

StoredDocument.prototype = {
	getUrl: function() {
		return this.url;
	},
	/**
	 * get a page based on page number
	 * @param page_num - the page number to get
	 */
	getPage: function(page, callback){
		$.get(
			this.url+"/"+this.id+"/"+page,
			function(data){
				if(data.success){
					data.number = page;
					callback(data);
				}else{
					callback(data.success);
				}
			}
		);
	}
};


/**
 * Document Reader 
 * this will allow encapsulate all functionality of showing a document
 */

var thumbViewer =  thumbViewer || {};

(function(){
	var storedDocument = null; 
	var subscribers = null;
	var currentPage = 0;
	
	/**
	 * Constructor for the document reader
	 * @param docId - the id of the document
	 * @param docUrl - the url for the 
	 * @param pages - an array of all the pages
	 */
	this.initialize = function(docId, docUrl, tPages){
		subscribers = new Array();
		storedDocument = new StoredDocument(docId, docUrl);
		totalPages = tPages;
	};
	
	/**
	 * Add a subscriber to recieve events
	 * @param subscriber - a function to call back once new page or any 
	 * other event is received 
	 */
	this.addSubscriber = function(subscriber){
		subscribers.push(subscriber);
	};
	
	/**
	 * broadcast information to all subscribers
 	* @param {Object} data, will be in format: TODO
	 */
	function broadcast(data){
		for(var i = 0; i < subscribers.length; i++){
			subscribers[i](data);
		}
	}
	

	/**
	 * get the url for the next page
	 */
	this.nextPage = function(){
		if(currentPage <= totalPages){
			currentPage += 1;
			storedDocument.getPage(currentPage, broadcast);
		}
			
	};
	
	/**
	 * get the url for the previous page
	 */
	this.previousPage = function(){
		if(currentPage > 1){
			currentPage -= 1;
			storedDocument.getPage(currentPage, broadcast);
		}
			
	};
	
	
}).apply(thumbViewer);


var pageViewer = pageViewer || {};

(function(){
	
	/**
	 * private function destroy
	 */
	function destroy(){
		$("html, body").css("overflow","auto");
		$("div#overlay-content").remove();
		$("div#overlay").hide();
		$("body").removeClass('background-off');
	};
	
	/**
	 * Initialize overlay
	 */
	this.initialize = function(){
		var html = new EJS({url: base_url+"assets/templates/documents/overlay.ejs"}).render();
		$(html).prependTo("body");
		$("html, body").css("overflow","hidden");
		$("body").addClass('background-off');
		$("div#overlay-body").scrollTop(0);
		$("div#overlay-body > p").html("<span id='page_number'></span>");
		$("div#overlay").show();
		$("div#close-overlay").click(function(e){
			destroy();
		});
	};
	
	this.loadPage = function(imgUrl, page_num, callback){
		$("div#overlay-body-img").append(
			"<a href='"+imgUrl+"' target='_blank'>"+ 
			"<img class='cpage' id='full-page' data-page="+page_num+" src='"+imgUrl+"' />"
			+"</a>"
		).data({page: page_num});
		// set the page number
		$("div#overlay-body > p > span#page_number").html(page_num);
    				
	};
	
	this.destroy = destroy();
	
	
	
	
}).apply(pageViewer);

