
function renderPage(pageData){
	$("div#page-"+pageData.number+" > img").attr('src', pageData.page);
}


$(document).ready(function(){
	docReader.initialize(docId, site_url+"/admin/document/page", totalPages);
	docReader.addSubscriber(renderPage);
	for(var i=0; i < totalPages; i++){
		var data = {page_number: (i+1)};
		var html = new EJS({url: base_url+'assets/templates/documents/thumbnail.ejs'}).render(data);
		$("div#doc-images").append(html);
		docReader.nextPage();
	}
});
