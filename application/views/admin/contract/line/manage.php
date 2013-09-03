<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Line Items</li>
</ul>  


		<div>
			Origin:
			<input id="origin" />
		</div>

		<div>
			Destination:
			<input id="destination" />
		</div>
		<?php foreach($containers as $container): ?>
		<div>
			<?php echo $container['text'] ?>
			<select>
				<?php foreach($currencies as $currency): ?>
				<option 
					value="<?php echo $currency->id ?>" 
					<?php if($customer->default_currency == $currency->id) { echo 'SELECTED="true"'; } ?>>
					<?php echo $currency->code; ?>
				</option>
				<?php endforeach; ?>
			</select>
			<input class="container-value" type="text" data-container-type='<?php echo $container['type'] ?>' />
		</div>
		<?php endforeach; ?>
		<div>
			Cargo:
			<select id="cargo_type">
			<?php foreach($cargo_types as $type): ?>
				<option value="<?php echo $type ?>"><?php echo $type ?></option>
			<?php endforeach; ?>
			</select>	
		</div>
		
		<div>
			Effective:<input type="text" id="effective" value="<?php echo $effective_date ?>" />
			Ends:<input type="text" id="enddate" value="<?php echo $expires_date ?>" />
		</div>
		
		
		<div>
			<button id="refresh-line-products" class="btn">Refresh Products</button>
		</div>
		<div id="results">
			<table id="line-item-products" class="table table-condensed">
				<tr>
					<th>Origin</th>
					<th>Destination</th>
					<th>Container</th>
					<th>Value</th>
					<th></th>
				</tr>
			</table> 
			<button id="save" class="btn btn-primary">Save</button> 
		</div>
		
		
		
</table>

<script language="javascript">
	var contract_id = <?php echo $contract->id; ?>;
</script>
