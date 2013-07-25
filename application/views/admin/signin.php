<div class="row-fluid">

	<div id="messages">
		<?php echo $this -> session -> flashdata("messages"); ?>
	</div>

	<form action="<?php echo site_url('admin/login/verify_admin_user'); ?>" method="post" accept-charset="utf-8">
		<fieldset>
			<legend>
				Administrator Login...
			</legend>
			<label>type in your email</label>
			<input name="email" type="text" placeholder="Email Address">
			<span class="help-block">Type in your email</span>
			<label>type in your password</label>
			<input name="password" type="password" placeholder="Password">
			<span class="help-block">The password you log in with</span>
			<input type="hidden" name="remember" value="true" />
			<button type="submit" class="btn">
				Submit
			</button>
		</fieldset>

	</form>

</div>