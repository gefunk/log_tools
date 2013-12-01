<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->_id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li class="active"><?php echo $contract->_id ?></li>
</ul>  

<section>
	<h2><small>Contract</small><?php echo $contract->number; ?></h2>
	<div>
		<em>Internal Id:</em><?php echo (string) $contract->_id; ?>		
	</div>
	<div>
		<em>Carrier:</em><?php echo $contract->carrier->name; ?>		
	</div>
	<div>
		<em>Start Date:</em><?php echo date('F d, Y', $contract->start_date->sec); ?>		
	</div>
	<div>
		<em>End Date:</em><?php echo date('F d, Y', $contract->end_date->sec); ?>	
	</div>
</section>


<ul class="nav nav-pills">
  <li>
    <a href="<?php echo site_url().'/admin/line/manage/'.$contract->_id ?>">Line Items</a>
  </li>
  <li><a href="#">Adjustments</a></li>
  <li><a href="<?php echo site_url().'/admin/document/manage/'.$contract->_id; ?>">Document</a></li>
  <li><a href="<?php echo site_url().'/admin/contract/ports/'.$contract->_id; ?>">Port Groups</a></li>
  <li><a href="<?php echo site_url().'/admin/contract/containers/'.$contract->_id; ?>">Containers</a></li>
  <li><a href="<?php echo site_url().'/admin/contract/cargo/'.$contract->_id; ?>">Cargo</a></li>
</ul>
