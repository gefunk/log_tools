
<ul class="breadcrumb">
	<li><a href="<?php echo site_url() . '/admin/contract/all/' . $customer -> id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url() . '/admin/contract/manage/' . $contract -> id; ?>"><?php echo $contract -> number; ?></a><span class="divider">/</span></li>
	<li class="active">Cargo</li>
</ul>  

<div>
	<input type="text" placeholder="Cargo Type" id="cargo_type" />
	<button id="add-cargo-type" class="btn btn-primary">Add Cargo Type</button>
</div>

<ul id="cargo-types">
	
</ul>


<script type="text/javascript" charset="utf-8">var contract_id =  '<?php echo $contract -> id; ?>';</script>
