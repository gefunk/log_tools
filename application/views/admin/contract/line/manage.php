<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Line Items</li>
</ul>  

<table class="table table-bordered">
	<tr>
		<th>Origin</th>
		<th>Destination</th>
		
	</tr>
</table>
