<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Export Code Search</title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/export_code_search.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<h4>Amfitir Tools</h4>
			<form id="search-form" class="form-search">
				<div class="input-append">
					<input id="search-term" type="text" class="span3 search-query" style="line-height:30px; height:30px;" placeholder="Search Export Codes">
					<button id="search-button" class="btn">Search</button>
				</div>
			</form>
		</div>
			
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		
		<script type="text/javascript">
			$(document).ready(function(){
				console.log("Document ready");
				$("form").submit(function(){
					$("dl").remove();
					console.log("Form submit handler");
					var search_term = $("#search-term").val();
					if(search_term.length > 0){
						var url = encodeURI("search/"+search_term);
						$.get(url, function(data){
							var html = "<dl class='dl-horizontal'>";
							for(var result in data){
								html += "<dt>"+data[result].code+"</dt>"+"<dd>"+data[result].descrip_2+"</dd>";
							}
							html += "</dl>";
							$("form").after(html);
						}, "json");
					}
					// prevent form submission
					return false;
				});
				
			});
		</script>
	</body>
</html>