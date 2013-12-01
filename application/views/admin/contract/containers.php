
<ul class="breadcrumb">
	<li><a href="<?php echo site_url() . '/admin/contract/all/' . $customer -> _id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url() . '/admin/contract/manage/' . $contract -> _id; ?>"><?php echo $contract -> number; ?></a><span class="divider">/</span></li>
	<li class="active">Containers</li>
</ul>  

<div>
	<label>Text</label><input id="container_text" type="text" />
	<label>Type</label>
	<select id="container_type">
		<?php foreach($container_types as $type): ?>
			<option value="<?php echo $type->id ?>"><?php echo $type->description; ?></option>
		<?php endforeach; ?>
	</select>
	<button id="add-container" class="btn btn-primary">Add Container</button>
</div>

<table id="container-table" class="table table-bordered">
	<tr>
		<th>Text</th>
		<th>Type</th>
	</tr>
	
		<?php
		if(isset($containers)){
			foreach($containers as $container):
		?>
		<tr>
			<td><?php echo $container['text']; ?></td>
			<td><?php echo $container['type_text']; ?></td>
			<td>
				<button class="delete-container" data-id='<?php echo $container['type']; ?>' class="btn btn-danger btn-mini">
					<i class="icon-trash"></i>
				</button>
			</td>
		</tr>
		<?php 
			endforeach;
		}
		?>
	
</table>


<script type="text/javascript" charset="utf-8">var contract_id =  '<?php echo $contract -> _id; ?>';</script>