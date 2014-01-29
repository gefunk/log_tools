$(document).ready(function(){
	
	/**
	 * Handler to change status for a user
	 */
	$("select.status").change(function(){
		var data = {status: $(this).val(),
					customer_id: $("table#users").data("customer-id"),
					user_id: $(this).data("user-id")};
		$.post(
			site_url+"/admin/users/set_status",
			data,
			function(data){
				if(!data.success){
					console.log("Something Bad happened: ", data);	
				}
			}
		);
	});
	
	
	/**
	 * Handler to change role for a user
	 */
	$("select.role").change(function(){
		var data = {role: $(this).val(),
					customer_id: $("table#users").data("customer-id"),
					user_id: $(this).data("user-id")};
		$.post(
			site_url+"/admin/users/set_role",
			data,
			function(data){
				if(!data.success){
					console.log("Something Bad happened: ", data);	
				}
			}
		);
	});
	
	
	/**
	 * Handler to reset email
	 */
	$("button.reset-password").click(function(){
		var data = {customer_id: $("table#users").data("customer-id"),
					user_id: $(this).data("user-id")};
		var $button = $(this);
		$button.addClass("disabled");
		$.post(
			site_url+"/admin/users/reset_password",
			data,
			function(data){
				if(!data.success){
					console.log("Was not able to reset password");	
				}else{
					$button.addClass("btn-success").text("Successfully Reset");
				}
			}
		);		
					
	});
	
});
