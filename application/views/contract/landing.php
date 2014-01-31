<div class="row-fluid">
	
	<div class="span12">
		<h4>View Contracts <small> Click on a contract to view page(s)</small></h4>
	</div>
	
	<div class="span12">
	<?php foreach($contracts as $contract): ?>
	<div class="span3">
		<div 
			class="carrier-img" 
			data-contract-id="<?php echo $contract->_id; ?>">
			<div class="carrier-sprite <?php echo $contract->carrier->sprite; ?>">
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	</div>
	
</div>
