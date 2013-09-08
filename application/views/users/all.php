

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
			
			<tr>
				<?php foreach($users as $user): ?>
				<td><?php echo $user['email']; ?></td>
				<td><?php echo ($user['active']) ? "active" : "Inactive"; ?></td>
				<td><?php echo $user['role']; ?></td>
				<?php endforeach; ?>
			</tr>
		</table>
		</div>
	
	</div>
</div>
