<div id="input-contract-line" class="well">
	<div class="row">
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
						<button id="use-port-groups" class="btn btn-small">Use Port Groups</button>
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
						<select id="cargo_type" name="cargo_type" data-placeholder="Cargo Type">
							<?php foreach($cargo_types as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->name." - ".$type->description; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<!-- for each container type create an input box with it -->
				<?php
					foreach($container_types as $container):
				?>
				<tr data-container-id="<?php echo $container->id; ?>">
					<td title="<?php echo $container->description; ?>"><?php echo $container->container_type; ?></td>
					<td>
						<div class="input-prepend">
							<span class="add-on">$</span>
							<input type="text" class="container_amount" placeholder="Amount">
						</div>
					</td>
					<td>
						<select class="container_currency_code" data-placeholder="Currency">
							<?php foreach($currencies as $currency): ?>
								<option value="<?php echo $currency->id ?>" data-symbol="<?php echo $currency->symbol; ?>" <?php if($currency->code == "USD") { echo "SELECTED='true'"; } ?>>
									<?php echo $currency->code." - ".$currency->description ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<td>
						<table id="added-containers" class="table">
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="input_entry_start_date" id="input_entry_start_date"  placeholder="Start Date" />
					</td>
					
					<td>
						to
						<input type="text" name="input_entry_end_date" id="input_entry_end_date"  placeholder="End Date" />
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
				
				<!-- Charges to go on all the lanes here -->
				<tr>
					<td colspan="3"><button id="add-lane-charge" class="btn btn-success">Add Lane Charges</button></td>
				</tr>
				<tr>
					
					<td colspan='3'>
						<table id="input-charges" class="table">
							
						</table>
					</td>
				</tr>
				
				<!-- Effective and Expiry dates -->
				
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

<div class="row">
	<div class="span12">
		<table id="lanes" class="table table-hover">
		</table>
	</div> <!-- end span 12 -->
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

</div>
<!-- values to pass to javascript -->
<script type="text/javascript" charset="utf-8">
	var carrier_id = "<?php echo $carrier_id ?>";
	var contract_start_date = "<?php echo $contract_start_date; ?>";
	var contract_end_date = "<?php echo $contract_end_date; ?>";
	var currencies = Array();
	var contract_id = "<?php echo $contract_id ?>";
	<?php foreach($currencies as $currency){?>
		currencies.push(
			{
				id : '<?php echo $currency->id; ?>', 
				code: '<?php echo $currency->code; ?>',
				desc: '<?php echo $currency->description?>',
				selected: '<?php if($currency->code == "USD") { echo "SELECTED"; } ?>'
			}
		);
	<?php } ?>
</script>