
<div class="well">
	<div class="row-fluid">
		<div class="span12">
			
			<table>
				<tr>
					<td>
						<input type="hidden" class="query-input" name="location" id="location" placeholder="Add Location"  style="width: 226px;"/>
					</td>
					<td>
						<select id="leg-type" name="leg-type" data-placeholder="Leg Type">
							<?php foreach($leg_types as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<select id="transport-type" name="transport-type" data-placeholder="Transport Type">
							<?php foreach($transport_types as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<button id="add-leg" class="btn btn-small btn-success"><i class="icon-plus"></i></button>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table id="route" class="table">
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<select id="container_type" name="container_type" data-placeholder="Container Type">
							<?php foreach($container_types as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->container_type." - ".$type->description; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<select id="cargo_type" name="cargo_type" data-placeholder="Cargo Type">
							<?php foreach($cargo_types as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->name." - ".$type->description; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<div class="input-prepend">
							<span class="add-on">$</span>
							<input type="text" id="amount" placeholder="Amount">
						</div>
					</td>
					<td>
						<select id="currency_code" data-placeholder="Currency">
							<?php foreach($currencies as $currency): ?>
								<option value="<?php echo $currency->id ?>" data-symbol="<?php echo $currency->symbol; ?>" <?php if($currency->code == "USD") { echo "SELECTED='true'"; } ?>>
									<?php echo $currency->code." - ".$currency->description ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<!-- classifications for lane -->
						<select id="tariff" name="tariff" multiple="multiple" data-placeholder="Tariff(s)">
							<?php foreach($tariffs as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->code." - ".$type->name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<select id="service" name="service" data-placeholder="Carrier Service">
							<?php foreach($services as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->code." - ".$type->name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<!-- action buttons -->
						<div class="span4">
							<button id="add-lane" class="btn btn-primary"><i class="icon-plus-sign"></i>Add Lane</button>
							<button id="clear-lane" class="btn btn-warning"><i class="icon-remove-sign"></i>Clear</button>
						</div>
					</td>
				</tr>
			</table>
			<input type="hidden" id="contract_id" value="<?php echo $contract_id ?>" />
		</div><!-- end span12 -->
	</div><!-- end row -->
</div><!-- end well -->

<div class="row-fluid">
	<div class="span12">
		<table id="lanes" class="table table-hover">
		</table>
	</div> <!-- end span 12 -->
</div>

<!-- values to pass to javascript -->
<script type="text/javascript" charset="utf-8">
	var carrier_id = "<?php echo $carrier_id ?>";
	var contract_start_date = "<?php echo $contract_start_date; ?>";
	var contract_end_date = "<?php echo $contract_end_date; ?>";
	var currencies = Array();
	<?php foreach($currencies as $currency){?>
		currencies.push(
			{
				id : '<?php echo $currency->id; ?>', 
				code: '<?php echo $currency->code; ?>',
				desc: '<?php echo $currency->description?>'
			}
		);
	<?php } ?>
</script>
