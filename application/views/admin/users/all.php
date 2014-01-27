<div class="span8">
	<button class="btn btn-primary pull-right">New User</button>
</div>

<table id="users" data-customer-id="<?php echo $customer->_id; ?>" class="table">
	<tr>
		<th>Username</th>
		<th>Role</th>
		<th>Status</th>
		<th>Reset</th>
	</tr>
	<?php 
	foreach($users as $user):
	?>
	<tr>
		<td><?php echo $user->email; ?></td>
		<td>
			<select class="role" data-user-id="<?php echo $user->_id; ?>">
				<option value="regular" <?php echo ($user->role == "regular") ? "selected='true'" : ""; ?> >Regular User</option>
				<option value="admin" <?php echo ($user->role == "admin") ? "selected='true'" : ""; ?>>Admin User</option>
			</select>
		</td>
		<td>
			<select class="status" data-user-id="<?php echo $user->_id; ?>">
				<option value="0" <?php echo ($user->active) ? "" : "selected='true'"; ?>>Inactive</option>
				<option value="1" <?php echo ($user->active) ? "selected='true'" : ""; ?>>Active</option>
			</select>
		</td>
		<td><button data-user-id="<?php echo $user->_id; ?>" class="btn btn-mini reset-password">Reset Password</button></td>
	</tr>
	<?php
	endforeach;
	?>
</table>