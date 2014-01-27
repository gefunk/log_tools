
function renderPage(pageData){
	$("div.page-"+pageData.number+" > img").one("load", function(){
		$(this).parents("div.doc-thumbnail").css("display", "inline-block");
	}).attr('src', pageData.page);
}


$(document).ready(function(){
	/**
	 * Draw all the document pages
	 */
	
	var thumbViewer = new ThumbViewer(docId, site_url+"/admin/document/page", totalPages);
	
	for(var i=0; i < totalPages; i++){
		var data = {page_number: (i+1), doc_id: docId};
		var html = new EJS({url: base_url+'assets/templates/documents/thumbnail.ejs'}).render(data);
		$("div#doc-images").append(html);
		thumbViewer.nextPage(renderPage);
	}
	
	/**
	 * Show the large size of the page when clicked on
	 *  
	 */
	$("div.doc-thumbnail").click(function(){
		/**
		* TODO - Fix page viewer to show large size page overlay
		* pageViewer.initialize();
		* pageViewer.loadPage(page_url, page_number);
		*/
		var page_number = $(this).data('page-num');
		var page_url = $(this).children("img").attr("src");
		window.open(page_url);
	});
	
	
	/**
	 * set up form
	 */
	$.get(
		site_url+"/admin/customer/all",
		function(data){
			for(var i=0; i < data.length; i++){
				var customer = data[i];
				$("<option value='"+customer.id+"'>"+customer.name+"</option>")
					.appendTo("select#customer");
			}
		}
	);
	
	$("select#customer").change(function(e){
		$("select#contract").html("");
		$.get(
			site_url+"/admin/contract/all/"+this.value+"/true",
			function(data){
				for(var i=0; i < data.length; i++){
				var contract = data[i];
				$("<option value='"+contract.id+"'>"+contract.carrier.name + " - " +contract.number+"</option>")
					.appendTo("select#contract");
				}
			}
		);
	});
	
});
