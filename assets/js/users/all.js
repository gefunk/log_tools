
$(document).ready(function(){
	/**
	 * change user status
	 */
	$("a.user-status").click(function(){
		var $visible_link = $(this).parents("div.btn-group").children("a.dropdown-toggle").children("span.text");
		var $status_link = $(this);
		// when the status changes we need to flip the switch to the other status
		var status_to_set = "0";
		if($(this).data('status') == '0'){
			status_to_set = "1";
		}
		var data = {
			user_identity: $(this).data("id"),
			status: $(this).data("status")
		};
		$.post(
			site_url+"/users/changestatus",
			data
		).done(function(data){
			$status_link.data('status', status_to_set);
			if(status_to_set == "0"){
				$visible_link.text("Active");
				$status_link.text("Deactivate");
			}else{
				$visible_link.text("Inactive");
				$status_link.text("Activate");
			}
		});
		
	});
	
	/**
	 * change user role
	 */
	$("a.role-change").click(function(){
		
		var $visible_link = $(this).parents("div.btn-group").children("a.dropdown-toggle").children("span.text"); 
		var changed_role = $(this).data("role");
		var data = {
			user_identity: $(this).data("id"),
			role: $(this).data("role")
		};
		$.post(
			site_url+"/users/changerole",
			data
		).done(function(data){
			$visible_link.text(changed_role); 
		});
	});
});
