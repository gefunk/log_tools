<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/select2.css" type="text/css" media="screen" title="no title" charset="utf-8">
<!-- heading -->
<div class="row-fluid">
	<div class="span10">
		<div id="contract-information">
				<h2><small>Contract Number:</small> <?php echo $contract_number; ?>
				<small>Customer:</small> <?php echo $customer; ?>
				<small>Carrier:</small> <?php echo $carrier; ?></h2>
		</div>
	</div>
</div>

<!-- lanes list -->
<div id="lanes-list">
	<?php foreach($lanes as $lane): ?>
	<ul>
		<li>
			<div><span>from:</span><?php echo $lane['port_load'][0]["port_name"].", ".$lane['port_load'][0]["country_name"]; ?></div>
			<div><span>to:</span><?php echo $lane['port_discharge'][0]["port_name"].", ".$lane['port_discharge'][0]["country_name"]; ?></div>
			<div><span>container:</span><?php echo $lane['container_type']; ?></div>
			<div><span>amount:</span><?php echo $lane['charge_amount']; ?></div>
			<div>
				<span>cargo:</span>
				<a href="#" data-toggle="tooltip" title="<?php echo $lane['cargo_type_id']; ?>"><?php echo $lane['cargo_type']; ?></a>
			</div>
		</li>
	</ul>
	<?php endforeach; ?>
</div>
