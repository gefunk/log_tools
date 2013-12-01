<div class="row-fluid">
	
	<div class="span12">
		<h4>View Contracts <small> Click on a contract to view page(s)</small></h4>
	</div>
	
	<div class="span12">
	<?php foreach($contracts as $contract): ?>
	<div class="span3">
		<img 
			class="carrier-img" 
			src="<?php echo base_url().'assets/img/carriers/'.$contract->image; ?>" 
			data-contract-id="<?php echo $contract->_id; ?>"
			data-pages="<?php echo $contract->number_of_pages; ?>" />
	</div>
	<?php endforeach; ?>
	</div>
	
</div>
