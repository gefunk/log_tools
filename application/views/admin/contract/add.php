<div class="row-fluid">
	<div class="span10">
		<form>
		  <fieldset>
		    <legend>New Contract</legend>
		    
			<label>Customer</label>
			<select name="customer" id="customer" size="1">
				<option value="none"></option>
				<?php foreach($customers as $customer): ?>
				<option value="<?php echo $customer->id ?>"><?php echo $customer->name ?></option>
				<?php endforeach; ?>
			</select>
		    <span class="help-block">Select a customer for the contract</span>
		    
			<label>Contract Number</label>
			<input type="text" name="contract_number" value="" id="contract_number" placeholder="Contract Number" />
			<span class="help-block">Enter the contract number on the contract</span>
			
			<label>Carrier</label>
			<select name="carrier" id="carrier" size="1">
				<option value="none"></option>
				<?php foreach($carriers as $carrier): ?>
				<option value="<?php echo $carrier->id; ?>"><?php echo $carrier->name ?></option>
				<?php endforeach; ?>
			</select>
			<span class="help-block">Select the carrier this contract belongs to</span>
			
			<label>Start Date</label>
			<input type="text" class="datepicker" name="start_date" value="" id="start_date"  placeholder="Start Date" />
			<span class="help-block">Start date on the contract</span>
			
			<label>End Date</label>
			<input type="text" class="datepicker" name="end_date" value="" id="end_date" placeholder="End Date" />
			<span class="help-block">End date on the contract</span>
			
			
		    <button type="submit" class="btn">Submit</button>
		  </fieldset>
		</form>
	</div>
</div>