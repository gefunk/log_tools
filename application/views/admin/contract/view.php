<!-- customer select box -->
<div class="row-fluid">
	<div class="span12">
		<h1>View Contracts for Customer</h1>
		<label for="customer">Select Customer</label>
		<select name="customer" id="customer">
				<option value = "0">-- Please Select --</option>
			<?php foreach($customers as $customer): ?>
				<option value="<?php echo $customer->id; ?>" 
						<?php if(!empty($customer_id) && $customer_id == $customer->id) { echo 'SELECTED'; } ?> >
					<?php echo $customer->name; ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>
</div>


<div class="row-fluid">
	<div class="span12">
		<?php if(isset($contracts)) {?>
		<h2>Contracts</h2>
		<table class="table">
			<tr>
				<th>Number</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Carrier Name</th>
				<th></th>
			</tr>
			<?php foreach($contracts as $row): ?>
			<tr>
				<td><?php echo $row->number; ?></td>
				<td><?php echo date('F d, Y', strtotime($row->start_date)); ?></td>
				<td><?php echo date('F d, Y', strtotime($row->end_date)); ?></td>
				<td><?php echo $row->carrier_name; ?></td>				
				<td>
					<button data-id="<?php echo $row->id; ?>" class="btn btn-small btn-info contract-view">View</button>
					<button data-id="<?php echo $row->id; ?>" class="btn btn-small btn-danger contract-delete">Delete</button>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php } else { ?>

			<h2> Please select a customer to view contracts </h2>

		<?php } // end if isset contracts ?>
	</div>
</div>

<?php if(isset($customer_id)) { ?>
<div class="row-fluid">
	<div class="span12">
		<?php echo form_open('admin/contract/add/'.$customer_id); ?>
		  <fieldset>
		    <legend>Add a New Contract</legend>
		    <label>Carrier</label>
			<select id="carrier" name="carrier">
				<option value="0">-- Please Select Carrier --</option>
				<?php foreach($carriers as $row): ?>
					<option value="<?php echo $row->id ?>"><?php echo $row->name; ?></option>
				<?php endforeach; ?>
			</select>
			<span class="help-block">The carrier which wrote this contract.</span>
			<label>Contract Number</label>
		    <input type="text" id="contract_number" name="contract_number" placeholder="Contract Number…">
		    <span class="help-block">The number at the beginning of the contract usually.</span>
		    
			<label>Start Date</label>
			<input type="text" class="datepicker" name="start_date" value="<?php echo set_value('start_date'); ?>" id="start_date"  placeholder="Start Date" />
			<span class="help-block">Start date on the contract</span>
			
			<label>End Date</label>
			<input type="text" class="datepicker" name="end_date" value="<?php echo set_value('end_date'); ?>" id="end_date" placeholder="End Date" />
			<span class="help-block">End date on the contract</span>
		
		    <button type="submit" class="btn btn-primary">add</button>
		  </fieldset>
		</form>
	</div>
</div>
<?php } // end is set customer id ?>