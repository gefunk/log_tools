$(document).ready(function(){
	$("#customer").change(function(){
		window.location.href = site_url+"/admin/contract/index/"+this.value;
	});
	
	// initialize datepicker
	$('.datepicker').datepicker();
	
	// attach click handler to delete buttons
	$("button.contract-delete").click(function(){
		var contract_id = $(this).data("id");
		window.location.href = site_url+"/admin/contract/delete/"+$("#customer").val()+"/"+contract_id;
	});
});