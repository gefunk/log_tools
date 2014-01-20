
function renderPage(pageData){
	$("div#page-"+pageData.number+" > img").one("load", function(){
		$(this).parents("div.doc-thumbnail").css("display", "inline-block");
	}).attr('src', pageData.page);
}


$(document).ready(function(){
	/**
	 * Draw all the document pages
	 */
	thumbViewer.initialize(docId, site_url+"/admin/document/page", totalPages);
	thumbViewer.addSubscriber(renderPage);
	for(var i=0; i < totalPages; i++){
		var data = {page_number: (i+1)};
		var html = new EJS({url: base_url+'assets/templates/documents/thumbnail.ejs'}).render(data);
		$("div#doc-images").append(html);
		thumbViewer.nextPage();
	}
	
	/**
	 * Show the large size of the page when clicked on
	 *  
	 */
	$("div.doc-thumbnail").click(function(){
		pageViewer.initialize();
		var page_number = $(this).data('page-num');
		var page_url = $(this).children("img").attr("src");
		pageViewer.loadPage(page_url, page_number);
		
	});
	
});
