<div class="row">
	<section>
		<div class="page-header">
			<h1><small>Customer</small><?php echo " ".$customer->name; ?></h1>
		</div>
		<div class="span2">
			<ul class="nav nav-tabs nav-stacked">
	  			<li class="<?php echo ($page == 'customers') ? 'active' : ''; ?>">
	    			<a href="<?php echo site_url().'/admin/customer/manager/'.$customer->_id; ?>">Overview</a>
	  			</li>
	  			<li class="<?php echo ($page == 'contracts') ? 'active' : ''; ?>">
	  				<a href="<?php echo site_url().'/admin/contract/all/'.$customer->_id; ?>">Contracts</a>
	  			</li>
	  			<li class="<?php echo ($page == 'users') ? 'active' : ''; ?>">
	  				<a href="#">Users</a>
	  			</li>
			</ul>
		</div>
	
	
		<div class="span10">
			
			
		