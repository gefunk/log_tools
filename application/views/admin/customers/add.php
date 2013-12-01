<div class="row">
	<div class="span12">
		<?php echo form_open('admin/customer/save'); ?>
		  <fieldset>
		    <legend>Add Customer</legend>
		    <label>Customer Name</label>
		    <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" />
		    <span class="help-block">Company name or Customer Name</span>
			<label>Currency</label>
			<select name="currency_code" id="currency_code">
				<?php foreach($currency_codes as $code): ?>
					<option value="<?php echo $code->code; ?>"><?php echo $code->code." - ".$code->description ?></option>
				<?php endforeach; ?>
			</select>
		    <span class="help-block">Default Currency for Customer</span>
			<label>Subdomain</label>
		    <input type="text" name="subdomain" id="subdomain" placeholder="Subdomain... e.g. demo" />
		    <span class="help-block">Subdomain for customer, their web address</span>
		    <button type="submit" class="btn btn-primary">Add</button>
		  </fieldset>
		</form>
	</div>
</div>