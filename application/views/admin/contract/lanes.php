<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/select2.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div class="row-fluid">
	<div class="span10">
		<div id="contract-information">
				<h2><small>Contract Number:</small> <?php echo $contract_number; ?>
				<small>Customer:</small> <?php echo $customer; ?>
				<small>Carrier:</small> <?php echo $carrier; ?></h2>
		</div>

		<form id="port-form">
			<input type="hidden" class="bigdrop" id="port-load" style="width:220px"/>
			<span>to</span>
			<input type="hidden" class="bigdrop" id="port-discharge" style="width:220px" />
		</form><!-- end form  -->
	</div>
</div>