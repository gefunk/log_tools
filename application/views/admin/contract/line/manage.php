<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Line Items</li>
</ul>  


		<div>
			Origin
			<input id="origin" />
		</div>

		<div>
			Destination
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
		
		
</table>

<script language="javascript">
	var contract_id = <?php echo $contract->id; ?>;
</script>
