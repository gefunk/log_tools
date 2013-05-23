<div class="row">

<div class="span2">
	<ul class="nav nav-list">
		<li class="">
			<a href="#global">
				<i class="icon-chevron-right"></i>
				Global styles
			</a>
		</li>
	</ul>
</div>


<div class="span5" class="vertical-divider">
	<section id="rule">
		<h4>Rule</h4>
		<ul id="condition-list">
		</ul>
		<div id="charge" class="holder">
		</div>
		<div id="value"class="holder">
		</div>
		<div id="apply"class="holder">
		</div>
		<div id="dates" class="holder">
		</div>
	</section>
	<section id="affected-lanes">
		<h5>Lane(s) Affected</h5>
		<button id="get-lanes-affected" class="btn">Get Lanes</button>
		<div id="affected-lanes" class="span12">
			No Lanes Affected
		</div>
	</section>
</div>

<div class="span3" class="vertical-divider">
	<section id="add-condition" class="right-input">
		<h4>Add Condition</h4>
		
		<div class="btn-group">
	    	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				Select condition (s)
	    		<span class="caret"></span>
	    	</a>
		    <ul id="condition-selector" class="dropdown-menu">
				<?php foreach($conditions as $condition): ?>
					<li>
						<a 
							href="#" 
							data-id="<?php echo $condition->id; ?>"
							data-description="<?php echo $condition->description; ?>"
							data-verb="<?php echo $condition->verb; ?>"
							data-source="<?php echo $condition->ref_data_source; ?>"
							>
								<?php echo $condition->name; ?>
						</a>
					</li>
				<?php endforeach; ?>
		    </ul>
	    </div>
		
		
		<div id="rule-holder">
			<div id="rule-desc">
			
			</div>
			<div id="rule-input">
				
			</div>
		</div>
		
		<button id="add-charge-condition" class="btn btn-primary"><i class="icon-plus"></i>Add Condition</button>
	</section>
	
	<section id="add-application" class="right-input">
		<div class="btn-group">
	    	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				Select a charge code
	    		<span class="caret"></span>
	    	</a>
		    <ul id="charge-code-selector" class="dropdown-menu">
				<?php foreach($charge_codes as $code): ?>
					<li>
						<a 
							href="#" 
							data-id="<?php echo $code->id; ?>"
							data-description="<?php echo $code->description; ?>"
							data-code="<?php echo $code->code; ?>"
							>
								<?php echo $code->code." - ".$code->description; ?>
						</a>
					</li>
				<?php endforeach; ?>
		    </ul>
	    </div>
	</section>
	
	
	<section id="add-application" class="right-input">
		<div>
			<div class="btn-group">
		    	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					How do you want to apply this?
		    		<span class="caret"></span>
		    	</a>
			    <ul id="application-selector" class="dropdown-menu">
					<?php foreach($application_types as $type): ?>
						<li>
							<a 
								href="#" 
								data-id="<?php echo $type->id; ?>"
								data-description="<?php echo $type->description; ?>"
								>
									<?php echo $type->type; ?>
							</a>
						</li>
					<?php endforeach; ?>
			    </ul>
		    </div>
		</div>
		<div id="application">

		</div>
	</section>

	<section id="values" class="right-input">
		<select id="currency_code" data-placeholder="Currency">
			<?php foreach($currencies as $currency): ?>
				<option value="<?php echo $currency->id ?>" data-symbol="<?php echo $currency->symbol; ?>" <?php if($currency->code == "USD") { echo "SELECTED='true'"; } ?>>
					<?php echo $currency->code." - ".$currency->description ?>
				</option>
			<?php endforeach; ?>
		</select>
		<div id="set-values">
			<label for="value">Value</label><input type="text" name="value" value="" id="value">
		</div>
		<button id="add-value" class="btn btn-primary"><i class="icon-plus"></i>Set Value</button>
	</section>
	
	<section id="dates" class="right-input">
			<label for="effective_on">Effective On</label><input type="text" name="effective_on" value="" id="effective_on">
			<label for="expires_on">Expires On</label><input type="text" name="expires_on" value="" id="expires_on">
			<button id="add-dates" class="btn btn-primary"><i class="icon-plus"></i>Set Dates</button>
	</section>
</div>

	
</div><!-- end row -->

<script type="text/javascript" charset="utf-8">
	var carrier_id = <?php echo $carrier_id; ?>
</script>
