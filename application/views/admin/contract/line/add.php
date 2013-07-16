
<form action="<?php echo site_url(); ?>/admin/line/save" method="post">
<div class="row">
	<div class="control-group">
	    <label class="control-label" for="origin-select">Origin</label>
	    <div class="controls input-append">
	      <input type="text" id="origin-select" name="origin-select" placeholder="Origin" class="origin" />
	      <select id="origin-port-group" class="port-group-selector origin">
	      	<option value="0">-- Select  a Group --</option>
	      	<?php foreach ($port_groups as $group): ?>
			<option value="<?php echo $group->id ?>"><?php echo $group->name; ?></option>
			<?php endforeach; ?>
	      </select>
	      <button class="btn toggle-group" type="button" data-toggle="off">Use Group</button>
	      <span class="help-block" data-reset="Select an origin"><ul><li>Select an origin</li></ul></span>
	    </div>
  	</div>
  	<div class="control-group">
	    <label class="control-label" for="origin">Destination</label>
	    <div class="controls input-append">
	      <input type="text" id="destination-select" placeholder="Destination" />
	      <select id="destination-port-group" class="port-group-selector">
	      	<option value="0">-- Select  a Group --</option>
	      	<?php foreach ($port_groups as $group): ?>
			<option value="<?php echo $group->id ?>"><?php echo $group->name; ?></option>
			<?php endforeach; ?>
	      </select>
	      <button class="btn toggle-group" type="button" data-toggle="off">Use Group</button>
	      <span class="help-block" data-reset="Select a destination"><ul><li>Select a destination</li></ul></span>
	    </div>
  	</div>
  	<div class="control-group">
	    <label class="control-label" for="cargo">Cargo</label>
	    <div class="controls">
	    <select id="cargo_type" name="cargo_type" data-placeholder="Cargo Type">
			<?php foreach($cargo_types as $type): ?>
				<option value="<?php echo $type -> id; ?>"><?php echo $type -> name . " - " . $type -> description; ?></option>
			<?php endforeach; ?>
		</select>
	    </div>
  	</div>
  	
  	<?php if(isset($services) && count($services) > 0){ ?>
  	<div class="control-group">
	    <label class="control-label" for="services">Service</label>
	    <div class="controls">
	    <select id="service" name="service" data-placeholder="Carrier Service">
			<?php foreach($services as $type): ?>
			<option value="<?php echo $type->id; ?>"><?php echo $type->code." - ".$type->name; ?></option>
			<?php endforeach; ?>
		</select>
	    </div>
  	</div>
  	<?php } // end if services ?>
  	<?php if(isset($tariffs) && count($tariffs) > 0){ ?>
  	<div class="control-group">
	    <label class="control-label" for="tariffs">Tariffs</label>
	    <div class="controls">
	    <select id="tariff" name="tariff" multiple="multiple" data-placeholder="Tariff(s)">
			<?php foreach($tariffs as $type): ?>
			<option value="<?php echo $type->id; ?>"><?php echo $type->code." - ".$type->name; ?></option>
			<?php endforeach; ?>
		</select>
	    </div>
  	</div>
  	<?php } // end if tarfifs ?>
  	
  	
  	
	<div class="input-append" id="from-date-decorate" data-date="<?php echo $effective_date; ?>" data-date-format="mm/dd/yyyy">
		<input type="text"  name="from_date" value="<?php echo $effective_date; ?>" id="from_date" name="effective_date" placeholder="Start Date">
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>
									
	<div class="input-append" id="to-date-decorate" data-date-format="mm/dd/yyyy">
		<input type="text" name="to_date" value="<?php echo $expires_date; ?>" name="expires_date" id="to_date" placeholder="End Date" />
		<span class="add-on"><i class="icon-calendar"></i></span>
	</div>
	
  	<!-- container section below -->
  	<table>
		  <tr>
		  	<?php
				foreach($container_types as $container):
			?>
		  	<td> 
		  		<div  class="input-append">
		  			<input type="text" 
			    		id="<?php echo $container -> container_type; ?>"
			    		name="container[<?php echo $container -> id; ?>][value]" 
			    		data-container-id="<?php echo $container->id; ?>" 
			    		placeholder="<?php echo $container -> container_type; ?>"  />
		  			<span class="add-on">
						<select class="container_currency_code" 
		    					data-placeholder="Currency" 
		    					name="container[<?php echo $container -> id; ?>][currency]">
							<?php foreach($currencies as $currency): ?>
							<option value="<?php echo $currency->id ?>" data-symbol="<?php echo $currency -> symbol; ?>" <?php
							if ($currency -> code == "USD") { echo "SELECTED='true'";}?>>
								<?php echo $currency->code." - ".$currency->description ?>
							</option>
							<?php endforeach; ?>
						</select>	
					</span>
				</div>
		  		
			    
	    	</td>
	    	<?php
				endforeach;
			?>
		  </tr>
	</table>
  	
	
</div>

<div class="row">
  <input type="submit" id="save" type="btn btn-primary" value="save">
</div>

<input type="hidden" name="origin" id="origin" />
<input type="hidden" name="destination" id="destination" />
<input type="hidden" name="origin_type" value="0" id="origin_type"/>
<input type="hidden" name="destination_type" value="0" id="destination_type"/>

</form>

<div id="contracts-physical" class="row">
	<table id="contract-page-controls">
		<tr>
			<td>
				<a id="contract-highlight-enable" class="btn btn-info" data-toggle="off">Highlight Off</a>
			</td>
			<td>
				<a id="contract-page-go-left" class='btn'><i class='icon-chevron-left icon-large'></i></a>		
			</td>
	  		<td>
	  			<input id="contract-page-number" type="text" />		
	  		</td>
	  		<td>
	  			<a id="contract-page-go-right" class='btn'><i class='icon-chevron-right icon-large'></i></a>		
	  		</td>
	  	</tr>
	</table>
  <div id="contract-pages">
    <div id='contract-page-loading'>
      <i class="icon-spinner icon-spin icon-4x"></i>
    </div>
    <img id="contract-page" src="blank.gif" />
  </div>
</div>


<!-- values to pass to javascript -->
<script type="text/javascript" charset="utf-8">
	var carrier_id = "<?php echo $carrier_id ?>";
	var contract_id = "<?php echo $contract_id ?>";
	var customer_id = '<?php echo $customer_id ?>';
	
	
</script>
