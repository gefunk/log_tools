

<div id="pad-wrapper" class="users-list">
  	<div class="row-fluid header">
  		 <h3>Users</h3>
  	</div>
	<div class="row-fluid">
		<div class="span12">
		<table class="table">
			<tr>
				<th>Email</th>
				<th>Status</th>
				<th>Role</th>
			</tr>
			<?php foreach($users as $user): ?>
			<tr>
				<td><?php echo $user['email']; ?></td>
				<td>
					<div class="btn-group">
					  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					  	<span class="text">
					    	<?php echo ($user['active']) ? "Active" : "Inactive"; ?>
					    </span>
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">
					    <li>
					    	<a 
					    		class="user-status"
					    		data-id="<?php echo $user['email']; ?>"
					    		data-status="<?php echo ($user['active']) ? "0" : "1"; ?>"
					    		href="#">
					    		<?php echo ($user['active']) ? "Deactivate" : "Activate"; ?>
					    	</a>
					    </li>
					  </ul>
					</div>
				</td>
				<td>
					<div class="btn-group">
					  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					  	<span class="text">
					    	<?php echo ($user['role']); ?>
					    </span>
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">
					    <li>
					    	<a 
					    		class="role-change"
					    		data-id="<?php echo $user['email']; ?>"
					    		data-role="admin"
					    		href="#">
					    		admin
					    	</a>
					    </li>
					    <li>
					    	<a 
					    		class="role-change"
					    		data-id="<?php echo $user['email']; ?>"
					    		data-role="regular"
					    		href="#">
					    		regular
					    	</a>
					    </li>
					  </ul>
					</div>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		</div>
	</div>
</div>
