$(document).ready(function(){
	
	// handle hover over element
	$("div.result-row").hover(
		function(){
			$(this).css("background-color", "#F5F5F5");
		},
		function(){
			$(this).css("background-color", "");
		}
	);
	
	
	// handle element click
	$("div.result-row").click(function(){
		if($(this).hasClass('well')){
			$(this).removeClass('well');
			$(this).children("div.result-detail").remove();
		}else{
			$(this).append('<div id="rd1" class="row-fluid result-detail"> <div class="span1"> <button type="button" class="btn"><i class="icon-filter"></i></button> </div> <div class="offset1"> <table class="table table-bordered"> <tr> <th>Description</th> <th>Price</th> <th>PPU</th> <th>Charge Applied by</th> <th>Effective Date</th> <th>Expirate Date</th> <th>Payment Due</th> </tr> <tr> <td>Agri Production GRI 03/01/13</td> <td>0.00</td> <td>0.00</td> <td>by container</td> <td>03/01/2013</td> <td>05/01/2013</td> <td>Prepaid</td> </tr> </table> </div> </div><!-- end child row -->');
			$(this).addClass("well");
		}
	});
	
	
	// handle filter button click
	$("button#filter").click(function(){
		if($("div#filter-input").is(":visible")){
			$("div#filter-input").hide("500");						
		}else{
			$("div#filter-input").show("500");			
		}

	})
	
	
});

