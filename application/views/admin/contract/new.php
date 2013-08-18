
<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li class="active">New</li>
</ul>  

		<?php echo form_open('admin/contract/save/'.$customer->id); ?>
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
