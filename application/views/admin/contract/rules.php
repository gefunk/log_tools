<div class="row-fluid">
	<h1>Charges</h1>
	<div class="span10 well">
		<div class="span3">
			<div class="btn-group">
		    	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					Select a condition
		    		<span class="caret"></span>
		    	</a>
			    <ul id="rule-selector" class="dropdown-menu">
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
		</div><!-- end selection of charge -->
		
		<div id="rule-holder" class="span7">
			<div id="rule-desc">
				
			</div>
			<div id="rule-input">
				<input id="rule-entry" class="bigdrop" type="hidden" style="width:440px">
			</div>
		</div>
		
		<div class="span2">
			<button id="add-charge-rule" class="btn btn-primary"><i class="icon-plus"></i>Add</button>
		</div>
		
	</div>	
</div>

<div class="row-fluid">
	<h3>Condition(s)</h3>
	<div id="conditions" class="span12">
		No Rules Yet
	</div>
</div>

<div class="row-fluid">
	<h3>Value to set</h3>
	<div id="set-values" class="span12">
		No Values Set Yet
	</div>
</div>


<div class="row-fluid">
	<h3>Lane(s) Affected</h3>
	<div id="affected-lanes" class="span12">
		No Lanes Affected
	</div>
	
</div>