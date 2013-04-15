
<div class="row-fluid">
	<div class="span12">
		<h1>Lanes</h1>
	</div> <!-- end span 12 -->
</div>


<div class="row-fluid">
	<div class="span12">
		<table id="lanes" class="table table-hover">
		</table>
	</div> <!-- end span 12 -->
</div>


<div class="well">
	<div class="row-fluid">
		<div class="span12">
			<div class="span4">
				<input type="hidden" class="query-input" name="location" id="location" placeholder="Add Location"  style="width: 226px;"/>
			</div>
			<div class="span3">
				<select id="leg-type" name="leg-type">
					<option value="0">-- Select Leg Type --</option>
					<?php foreach($leg_types as $type): ?>
						<option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="span3">
				<select id="transport-type" name="transport-type">
					<option value="0">-- Select Transport Type --</option>
					<?php foreach($transport_types as $type): ?>
						<option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="span2">
				<button id="add-leg" class="btn btn-small btn-success"><i class="icon-plus"></i></button>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span12">
			<table id="route" class="table">
				<tr>
					<th>Location</th>
					<th>Service type</th>
					<th>Leg type</th>
					<th></th>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span12">
			<div class="span4">
				<select id="container_type" name="container_type">
					<option value="0">-- Select Container type --</option>
					<?php foreach($container_types as $type): ?>
						<option value="<?php echo $type->id; ?>"><?php echo $type->container_type." - ".$type->description; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="span4">
				<select id="cargo_type" name="cargo_type">
					<option value="0">-- Select Cargo --</option>
					<?php foreach($cargo_types as $type): ?>
						<option value="<?php echo $type->id; ?>"><?php echo $type->description; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="span4">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-calendar"></i></span>
					<input type="text" class="datepicker" name="effective_date" placeholder="Effective Date" id="effective_date">
				</div>
			</div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
			<div class="input-prepend">
				<span class="add-on">$</span>
				<input type="text" id="amount" placeholder="Amount">
			</div>
		</div>
		<div class="span2">
			<select id="currency_code">
				<?php foreach($currencies as $currency): ?>
					<option value="<?php echo $currency->id ?>"><?php echo $currency->code." - ".$currency->description ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="row-fluid">
		<div class="span4">
			<button id="add-lane" class="btn btn-primary"><i class="icon-plus-sign"></i>Add Lane</button>
			<button id="clear-lane" class="btn btn-warning"><i class="icon-remove-sign"></i>Clear</button>
		</div>
	</div>
	
	<input type="hidden" id="contract_id" value="<?php echo $contract_id ?>" />
	
</div><!-- end well -->