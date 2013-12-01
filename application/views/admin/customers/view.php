<div class="row-fluid">
	<div class="span12">
		<h1>Customers</h1>
		<table class="table">
			<tr>
				<th>Name</th>
				<th>Currency</th>
				<th>Subdomain</th>
			</tr>
			<?php foreach($customers as $row): ?>
			<tr data-id="<?php echo $row->_id; ?>">
				<td><?php echo $row->name; ?></td>
				<td><?php echo $row->currency; ?></td>
				<td><?php echo $row->subdomain; ?></td>
				<td><a href="<?php echo site_url().'/admin/customer/manager/'.$row->_id; ?>" class="btn btn-info btn-mini">Manage</a></td>				
			</tr>
			<?php endforeach; ?>
		</table>
		
		<a href="<?php echo site_url().'/admin/customer/add'; ?>" class="btn btn-primary">New Customer</a>
	</div>
	
</div>

