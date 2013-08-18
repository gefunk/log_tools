<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li class="active"><?php echo $contract->number ?></li>
</ul>  

<section>
	<h2><small>Contract</small><?php echo $contract->number; ?></h2>
	<div>
		<em>Internal Id:</em><?php echo $contract->id; ?>		
	</div>
	<div>
		<em>Carrier:</em><?php echo $contract->carrier; ?>		
	</div>
	<div>
		<em>Start Date:</em><?php echo date('F d, Y', strtotime($contract->start_date)); ?>		
	</div>
	<div>
		<em>End Date:</em><?php echo date('F d, Y', strtotime($contract->end_date)); ?>	
	</div>
</section>


<ul class="nav nav-pills">
  <li>
    <a href="<?php echo site_url().'/admin/line/all/'.$contract->id ?>">Line Items</a>
  </li>
  <li><a href="#">Adjustments</a></li>
  <li><a href="<?php echo site_url().'/admin/contract/document/'.$contract->id; ?>">Document</a></li>
  <li><a href="<?php echo site_url().'/admin/contract/ports/'.$contract->id; ?>">Port Groups</a></li>
</ul>
