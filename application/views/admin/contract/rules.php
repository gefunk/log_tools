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
		<ul id="condition-list">
		</ul>
	</section>
	<section id="affected-lanes">
		<h3>Lane(s) Affected</h3>
		<button id="get-lanes-affected" class="btn">Get Lanes</button>
		<div id="affected-lanes" class="span12">
			No Lanes Affected
		</div>
	</section>
</div>

<div class="span3" class="vertical-divider">
	<section id="add-condition">
		<h4>Add Condition</h4>
		
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
		
		
		<div id="rule-holder">
			<div id="rule-desc">
			
			</div>
			<div id="rule-input">
				<input id="rule-entry" class="bigdrop" type="hidden" style="width:440px">
			</div>
		</div>
		
		<button id="add-charge-condition" class="btn btn-primary"><i class="icon-plus"></i>Add</button>
	</section>
	
	
	<section id="add-application">
		<h3>How to Apply?</h3>
		<div>
			<div class="btn-group">
		    	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					Select a condition
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

	<section id="values">
		<h3>Value to set</h3>
		<div id="set-values" class="span12">
			No Values Set Yet
		</div>
	</section>
	
</div>

	
</div><!-- end row -->
