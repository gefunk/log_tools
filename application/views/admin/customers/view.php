<div class="row">
	<div class="span9">
		<table>
			<tr><th>ID</th><th>Name</th></tr>
			<?php foreach($customers as $row): ?>
			<tr data-id="<?php echo $row->id; ?>">
				<td><?php echo $row->id; ?></td>
				<td><?php echo $row->name; ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>