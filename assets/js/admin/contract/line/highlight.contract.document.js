
/**
 * handles highlighting a contract page
 */
var contractHighlighter = {
	initialize: function(page_element, contract_id){
		contractHighlighter.page_element = page_element;
		contractHighlighter.page = 0;
		contractHighlighter.attachHandler();
		contractHighlighter.contract_id = contract_id;
		contractHighlighter.position = 0;
	},
	attachHandler: function(page_element){
		contractHighlighter.page_element.click(function(e) {
				var y = e.pageY - $(this).offset().top;
    			var element_height = contractHighlighter.page_element.height();
    			var height_percent = y / element_height;
    			console.log("percent height", height_percent);
			if(contractHighlighter.position <= 0){
    			contractHighlighter.position = height_percent;	
			}else{
				var height = 0;
				var position = 0;
				// position is less than original click
				if(height_percent < contractHighlighter.position){
					position = height_percent;
					height = contractHighlighter.position-position;
				}else{
					height = contractHighlighter.position- height_percent;
					position = contractHighlighter.position;
				}
				
				// reset the position
				contractHighlighter.position = 0;
				
				$.post(
					site_url+"/contract/add_highlight",
					{
						contract_id: contractHighlighter.contract_id,
						page: contractHighlighter.page,
						height: (height),
						position: (position)
					}
				).done(function(data){
					contractHighlighter.addHighlight(data[0].id, position, height);
				});
			}
    		
  		});	
	},
	addHighlight: function(highlight_id, position, height){
		var style = "top:"+(position*100)+"%;height:"+(height*100)+"%";
		var html = '<div id="'+highlight_id+'" class="highlighter" style="'+style+'">&nbsp;</div>';
		console.log("should be adding highlights: ", html);
		contractHighlighter.page_element.parent().append(html);
	},
	getHighlightsForPage: function(page){
		contractHighlighter.page = page;
		$.get(
			site_url+"/contract/get_highlights/"+contractHighlighter.contract_id+'/'+page,
			function(data){
				if(data[0] != null){
					for(var key in data[0]){
					// put highlights on page
						console.log("Data in Key ", data[0][key], " Key ", key);
						if(data[0][key] != null && data[0][key].position > 0){
							console.log("should be appending higlight ", data[0][key].position);
							contractHighlighter.addHighlight(data[0][key].id, data[0][key].position, data[0][key].height);	
						}
					}
				}
			}
		);
	}
	
}// end contract Highlighter
