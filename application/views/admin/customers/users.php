<div class="row">
	<div class="span9">
		<table>
			<tr>
				<th>First Name</th>
				<th>Last Name</th>
				<th>email</th>
				<th>Phone Number</th>
			</tr>
			<?php foreach($users as $row): ?>
			<tr data-id="<?php echo $row->id; ?>">
				<td><?php echo $row->first_name; ?></td>
				<td><?php echo $row->last_name; ?></td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo $row->phone_num; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>