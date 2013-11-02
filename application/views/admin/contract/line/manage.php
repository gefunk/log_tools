<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Line Items</li>
</ul>  

<div id="lineitem_list">
	
	
	
	<?php 
	
	function port_view($port)
	{
		return $port->name.
			(isset($port->state) ? ",".$port->state : "").
			$port->country_code. "(".$port->country_code.$port->port_code.")"; 
	}
	
	#var_dump($line_items);
	
	foreach($line_items as $line_item): 	
	?>
	<div lineitem-id="<?php echo $line_item['_id']; ?>">
		<ul>
			<li >
				Origin: 
				<?php 
					if($line_item['origin']['type'] == 'port'){
						echo port_view($line_item['origin']['value']);
					}else if($line_item['origin']['type'] == 'port_group'){
						echo $line_item['origin']['value']->name;
					}
				?>
				<span class="label"><?php echo $line_item['origin']['type']; ?></span>
			</li>
			<li>
				Destination: 
				<?php
					if($line_item['destination']['type'] == 'port'){
						echo port_view($line_item['destination']['value']);
					}else if($line_item['destination']['type'] == 'port_group'){
						echo $line_item['destination']['value']->name;
					}
				?>
				<span class="label"><?php echo $line_item['destination']['type']; ?></span>
			</li>
			<li>
				<span class="label">start</span>
				<?php echo $line_item['effective']; ?>
				<span class="label">end</span>
				<?php echo $line_item['expires']; ?>
			</li>
			<li>
				
				<div>
					<span class="label label-info">20 Foot Standard</span>
					$500
				</div>
				<div>
					<span class="label label-info">40 Foot Standard</span>
					$700
				</div>
				<div>
					<span class="label label-info">45 Foot Standard</span>
					$800
				</div>
			</li>
		</ul>		
	</div>
	<?php endforeach; ?>
</div>


<script language="javascript">
	var contract_id = <?php echo $contract->id; ?>;
</script>
