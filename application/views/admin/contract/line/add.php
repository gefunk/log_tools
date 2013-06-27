<div class="row">
	<div class="control-group">
	    <label class="control-label" for="origin-select">Origin</label>
	    <div class="controls input-append">
	      <input type="text" id="origin-select" placeholder="Origin" />
	      <select id="origin-port-group" class="port-group-selector">
	      	<?php foreach ($port_groups as $group): ?>
			<option><?php echo $group->name; ?></option>
			<?php endforeach; ?>
	      </select>
	      <button class="btn toggle-group" type="button" data-toggle="off">Use Group</button>
	      <span class="help-block">Select an origin.</span>
	    </div>
  	</div>
  	<div class="control-group">
	    <label class="control-label" for="origin">Destination</label>
	    <div class="controls input-append">
	      <input type="text" id="destination-select" placeholder="Destination" />
	      <select id="destination-port-group" class="port-group-selector">
	      	<?php foreach ($port_groups as $group): ?>
			<option><?php echo $group->name; ?></option>
			<?php endforeach; ?>
	      </select>
	      <button class="btn toggle-group" type="button" data-toggle="off">Use Group</button>
	      <span class="help-block">Select a destination</span>
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
  	<?php
	foreach($container_types as $container):
	?>
	<div class="control-group">
	    <label class="control-label" for="<?php echo $container -> container_type; ?>"><?php echo $container -> container_type . " "; ?> Container Cost</label>
	    <div class="controls">
	    <select class="container_currency_code" data-placeholder="Currency">
							<?php foreach($currencies as $currency): ?>
								<option value="<?php echo $currency->id ?>" data-symbol="<?php echo $currency -> symbol; ?>" <?php
								if ($currency -> code == "USD") { echo "SELECTED='true'";
								}
 ?>>
									<?php echo $currency->code." - ".$currency->description ?>
								</option>
							<?php endforeach; ?>
		</select>
	      <input type="text" id="<?php echo $container -> container_type; ?>" placeholder="<?php echo $container -> container_type; ?>"  />
	    </div>
  	</div>
	<?php
		endforeach;
	?>
</div>

<input type="hidden" name="origin" value="" id="origin"/>
<input type="hidden" name="destination" value="" id="destination"/>