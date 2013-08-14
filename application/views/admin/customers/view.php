<div class="row-fluid">
	<div class="span12">
		<h1>Customers</h1>
		<table class="table">
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Currency</th>
				<th>Subdomain</th>
			</tr>
			<?php foreach($customers as $row): ?>
			<tr data-id="<?php echo $row->id; ?>">
				<td><?php echo $row->id; ?></td>
				<td><?php echo $row->name; ?></td>
				<td><?php echo $row->currency_code; ?></td>
				<td><?php echo $row->subdomain; ?></td>
				<td><a href="<?php echo site_url().'/admin/customer/manager/'.$row->id; ?>" class="btn btn-info btn-mini">Manage</a></td>				
			</tr>
			<?php endforeach; ?>
		</table>
		
		<button class="btn btn-primary">New Customer</button>
	</div>
	
</div>

