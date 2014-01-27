
function renderPage(pageData){
	$("div#"+pageData.doc_id+" div.page-"+pageData.number+" > img").one("load", function(){
		$(this).parents("div.doc-thumbnail").css("display", "inline-block");
	}).attr('src', pageData.page);
}

var thumbnailDocuments = new Array();

$(document).ready(function(){
	
	/**
	 * show each document on the page
	 */
	$("div.doc-thumbnails").each(function(index, thumbnailContainer){
		
		var $thumbnailContainer = $(thumbnailContainer);
		var docId = $thumbnailContainer.attr("id");
		$.get(site_url+"/admin/document/get_total_pages/"+docId,function(data){
			thumbnailDocuments[docId] = new ThumbViewer(docId, site_url+"/admin/document/page", data.total);
			for(var i=0; i < 3; i++){
				var data = {page_number: (i+1), doc_id: docId};
				var html = new EJS({url: base_url+'assets/templates/documents/thumbnail.ejs'}).render(data);
				$thumbnailContainer.append(html);
				thumbnailDocuments[docId].nextPage(renderPage);
			}
		});
	});
	
	/**
	 * Show the large size of the page when clicked on
	 *  
	 */
	$("div.doc-thumbnails").on("click","div.doc-thumbnail", function(){
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
	 * add tag to document
	 */
	$("button.add-doc-tag").click(function(){
		var doc_id = $(this).data("doc-id");
		var $tag_input = $(this).next("input.doc-tag-input");
		var tag = $tag_input.val();
		$.post(
			site_url+"/admin/document/add_tag",
			{
				"document_id": doc_id,
				"tag": tag
			},
			function(data){
				if(data){
					var html = new EJS({url: base_url+'assets/templates/tag.ejs'}).render({doc_id: doc_id, tag: tag});
					$("div#doc-"+doc_id+" > div.doc-tags").append(html);
					$tag_input.val("");
				}
			}
		);
	});
	
	/**
	 * remove tag
	 */
	$("div.doc-tags").on("click", "i.delete-doc-tag", function(){
		var $tag = $(this).parent("span.doc-tag");
		var tag = $tag.text().trim();
		var doc_id = $tag.children("i.delete-doc-tag").data("doc-id");
		$.post(
			site_url+"/admin/document/remove_tag",
			{
				"document_id": doc_id,
				"tag": tag
			},
			function(data){
				if(data){
					$tag.remove();
				}
			}
		);
	});
	
});