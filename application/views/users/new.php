
<div id="pad-wrapper" class="new-user">
  
	<div class="row-fluid header">
		<h2>Create a new user</h2>
	</div>

	<div class="row-fluid form-wrapper">
		<div class="span9 with-sidebar">
			<div class="container">
				<form action="<?php echo site_url(); ?>/users/add" method="POST" class="new_user_form inline-input" id="new-user-form">
					<div class="span12 field-box">
						<label>Name:</label>
						<input type="text" name="name" class="span9">
					</div>
					<div class="span12 field-box">
						<label>Email:</label>
						<input type="text" name="email" class="span9">
					</div>
					<div class="span12 field-box">
						<label>Phone:</label>
						<input type="text" name="phone" class="span9">
					</div>
					<div class="span12 field-box textarea">
						<label>Notes:</label>
						<textarea class="span9" name="notes"></textarea>
					</div>
					<div class="span11 field-box actions">
						<input type="submit" value="Create user" class="btn-glow primary">
						<span>OR</span>
						<input type="reset" class="reset" value="Cancel">
					</div>
				</form>
			</div>

		</div>
		<div class="span3 form-sidebar pull-right">
			<?php if(validation_errors()) { ?>
				<div class="alert alert-danger">
					<?php echo validation_errors(); ?>	
				</div>
			<?php } ?>
			
			<?php if(isset($success)){ ?>
				<div class="alert alert-success">
					<?php echo $success; ?>	
				</div>
			<?php } ?>
			
			<div class="alert alert-info hidden-phone">
				<i class="icon-lightbulb pull-left"></i>
				The email address entered will be used as the user's login
			</div>
			<h6>Notes</h6>
            <p>
            	After Saving the user, the new user will receive an email with instructions on how to 
            	login to Amfitir.com
            </p>
			<p>The user will have to set their password on initial login</p>
		</div>
	</div>
</div>