
<div class="span8">
	<ul class="nav nav-tabs">
	  <li>
	    <a href="<?php echo site_url().'/admin/users/all/'.$customer->_id; ?>">All Users</a>
	  </li>
	  <li class="active"><a href="#">New User</a></li>
	</ul>
	<form action="<?php echo site_url(); ?>/admin/users/add" method="POST" id="new-user-form">
		<div class="span4">
			<label>Name:</label>
			<input type="text" name="name">
		</div>
		<div class="span4">
			<label>Email:</label>
			<input type="text" name="email">
			<span class="message"></span>
		</div>
		<div class="span4">
			<label>Phone:</label>
			<input type="text" name="phone" class="span9">
			<span class="message"></span>
		</div>
		<div class="span4 textarea">
			<label>Notes:</label>
			<textarea class="span9" name="notes"></textarea>
		</div>
		<input type="hidden" name="customer_id" value="<?php echo $customer->_id; ?>" />
		<div class="span4">
			<input type="submit" value="Create user" class="btn btn-primary">
		</div>
	</form>
</div>