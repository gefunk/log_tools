
/**
 * handles highlighting a contract page
 */
var contractHighlighter = {
	initialize: function(page_element, contract_id){
		//contractHighlighter.attachHandler(page_element);
		contractHighlighter.contract_id = contract_id;
	},
	attachHandler: function(page_element){
		page_element.click(function(e) {
    		var offset = $(this).offset();
    		alert(e.clientX - offset.left);
    		alert(e.clientY - offset.top);
  		});	
	},
	addHighlight: function(highlight_id, position, height){
		var style = "top:"+position+"px;height:"+height+"px";
		var html = '<div id='+highlight_id+'class="highlighter" style='+style+'>&nbsp;</div>';
		console.log("should be adding highlights: ", html);
		page_element.append(html);
	},
	getHighlightsForPage: function(page){
		$.get(
			site_url+"/contract/get_highlights/"+contractHighlighter.contract_id+'/'+page,
			function(data){
				for(var key in data){
					// put highlights on page
					console.log("Data in Key ", data[key][0], " Key ", key);
					if(data[key][0] != null && data[key][0].position > 0){
						console.log("should be appending higlight ", data[key].position);
						contractHighlighter.addHighlight(data[key][0].id, data[key][0].position, data[key][0].height);	
					}
				}
			}
		);
	}
	
}// end contract Highlighter
