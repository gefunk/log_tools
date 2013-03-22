<div class="row-fluid">
	<div class="span10">
		<div id="contract-information">
				<h2><small>Contract Number:</small> <?php echo $contract_number; ?>
				<small>Customer:</small> <?php echo $customer; ?>
				<small>Carrier:</small> <?php echo $carrier; ?></h2>
		</div>
		<?php echo validation_errors(); ?>
		<?php echo form_open('admin/contract/addrules/'.$contract_number); ?>
			<fieldset>
			    <legend>Add Rules for Contract</legend>
			
				<label>Enter the name of the charge</label>
				<input type="text" name="name" value="<?php echo set_value('name'); ?>" id="name" placeholder="Name" />
				<span class="help-block">Enter the name of this charge as it appears on the contract</span>
				
				<label>Enter a charge code</label>
				<input type="text" name="code" value="<?php echo set_value('code'); ?>" id="code" placeholder="Name" />
				<span class="help-block">Enter the code of this charge as it appears on the contract</span>
			
				<label>How does this rule apply?</label>
				<select name="rule_application_type" id="rule_application_type" size="1">
					<option value="none"></option>
					<?php foreach($application_types as $type): ?>
					<option value="<?php echo $type->id ?>"><?php echo $type->type ?></option>
					<?php endforeach; ?>
				</select>
			    <span class="help-block">Select how to apply this charge on this contract, by TEU, flat charge, by Container</span>
		
			
				<label>Which currency is listed on this contract?</label>
				<select name="currency" id="currency" size="1">
					<option value="none"></option>
					<?php foreach($currencies as $currency): ?>
					<option value="<?php echo $currency->id; ?>" <?php if($customer_default_currency_code == $currency->id) {echo "SELECTED"; } ?>>
						<?php echo $currency->description; ?>
					</option>
					<?php endforeach; ?>
				</select>
			    <span class="help-block">Select which currency this charge is listed in on the contract</span>
		
				<label>What is the amount of this charge?</label>
				<input type="text" name="value" value="<?php echo set_value('value'); ?>" id="value" placeholder="Charge Amount" />
				<span class="help-block">Enter the amount of this charge, it should be what is listed on the contract, do not enter a calculation of the charge, only the charge as it appears on the contract</span>
			
				
				<label>When does this rule apply?</label>
				<div id="rule_application_div">
					<select name="rule_application" id="rule_application" size="1">
						<option value="none"></option>
						<?php foreach($application_rules as $rule): ?>
						<option 
							data-verb="<?php echo $rule->verb; ?>" 
							data-source="<?php echo $rule->ref_data_source; ?>" 
							value="<?php echo $rule->id ?>">
							<?php echo $rule->name ?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div id="rule-application-entry">
					<div class="entry">
						
					</div>
					<div class="verb">
						is
					</div>
					<div class="values">
					</div>
				</div>
			    <span class="help-block">Select when to apply this charge on this contract, When the port of destination is ... or the Port of Loading is ...</span>
				

				<button type="submit" class="btn btn-primary">Submit</button>
			</fieldset>
		</form><!-- end form  -->
		<table class="table table-striped">
			<caption>List of all the rules that have been entered for this contract</caption>
			<thead>
				<tr>
					<th>Rule Name</th>
					<th>Application type</th>
					<th>Currency</th>
					<th>Amount</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($charge_rules as $charge_rule): ?>
				<tr>
					<td><?php echo $charge_rule->name; ?></td>
					<td><?php echo $charge_rule->application_type; ?></td>
					<td><?php echo $charge_rule->currency; ?></td>
					<td><?php echo $charge_rule->value; ?></td>
				</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	var contract_id = <?php echo $contract_id; ?>;
</script>