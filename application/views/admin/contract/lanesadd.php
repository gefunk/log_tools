<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/select2.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div class="row-fluid">
	<div class="span10">
		<div id="contract-information">
				<h2><small>Contract Number:</small> <?php echo $contract_number; ?>
				<small>Customer:</small> <?php echo $customer; ?>
				<small>Carrier:</small> <?php echo $carrier; ?></h2>
		</div>
	</div>
	
	
</div>
<div class="row-fluid">
	<div class="span5">
		<input type="hidden" class="bigdrop" id="port-load" style="width:100%"/>
	</div>
	<div class="span1">
		to
	</div>
	<div class="span5">
		<input type="hidden" class="bigdrop" id="port-discharge" style="width:100%" />
	</div>
</div>
<div class="row-fluid">
	<div id="container-types" class="span5">
		<label>Container Types</label>
	</div>
	<div class="span3 offset1">
		<label>Base Container Charge</label>
		<div class="span6 input-prepend input-append">
			<span class="add-on">$</span>
			<input id="dollars" type="text" style="text-align:right;width: 80%;">
			<input id="cents" type="text" value="00" style="width:20%;">
		</div>
	</div>
	<div class="span3">
		<label>Charge Code</label>
		<input type="text" placeholder="Charge Code" id="charge_code" name="charge_code" />
	</div>
</div>

<div class="row-fluid">
	<div class="span2">
		<button id="add-lane" type="button">Add Container Charge</button>
	</div>
</div>

<script type="text/javascript" charset="utf-8">
	var contract_id = <?php echo $contract_id; ?>;
	var carrier_id = <?php echo $carrier_id; ?>;
</script>