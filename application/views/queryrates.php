
<div id="query-selection">
	
	<div class="row-fluid">
		<div class="span12">
			
			<table id="query-selection">
				<tr>
					<td>
						<table>
							<tr>
								<td>
								<input type="hidden" class="query-input" name="origin" id="origin" placeholder="Origin city"  style="width: 226px;"/>
								</td>
								<td class="to">to</td>
								<td>
									<input type="hidden" class="query-input" name="destination" id="destination" placeholder="Destination city" style="width:226px;">			
								</td>
							</tr>
							<tr>
								<td>
									<div class="input-append" id="from-date-decorate" data-date-format="mm/dd/yyyy">
								    	<input type="text"  name="from_date" value="" id="from_date" placeholder="Start Date">
								    	<span class="add-on"><i class="icon-calendar"></i></span>
								    </div>
									
								</td>
								<td class="to">to</td>
								<td>
									<a class="btn btn-small" id="to-date" data-date-format="mm/dd/yyyy">
								    	<i class="icon-calendar"></i>
								    </a>
									
								</td>
							</tr>
						</table>
					</td>
					<td>
						<button type="button" id="search" class="btn btn-primary button-input"><i class="icon-search"></i></button>
					</td>
				</tr>
			</table>
		</div>
	</div>

</div><!-- query input selection -->

<div id="filters">
	<div class="row-fluid">
		<div class="btn-group">
	    	<a class="btn btn-small btn-info dropdown-toggle" data-toggle="dropdown" href="#">
	    		Sort
	    		<i class="icon-sort"></i>
	    	</a>
	    	<ul class="dropdown-menu">
				<li><a href="#">by distance to origin</a></li>
				<li><a href="#">by distance to destination</a></li>
				<li><a href="#">by cost</a></li>
				<li><a href="#">by price</a></li>
	    	</ul>
	    </div>
	</div>
</div>

<div class="result-row">
	

	<div class="row-fluid">
		<div class="span1 carrier-logo">
			<img src="<?php echo base_url(); ?>assets/img/carriers/maersk.svg" width="64px" height="64px">
		</div>
		<div class="span9 rate-body">
			<div class="rate-heading">
				<span id="origin-city" class="primary-city">Atlanta, GA, US</span>&rarr;<span class="via-city">Savannah, GA, US</span>&rarr;<span id="destination-city"  class="primary-city">Shanghai, CN</span>
			</div>
			<div class="rate-subtext">
				<div class="span3">
					<span class="info">service:</span>
						CY / CY
				</div>
				<div class="span3">
						<span class="info">commodity:</span>
							General Cargo
				</div>
				<div class="span3">
						<span class="info">container:</span>
						FCL - 40
				</div>
			</div>
		</div><!-- end class rate-body -->
		<div class="span2 rate-price">
			
			<div id="buy-rate"><span class="info">base:</span>$1600</div>
			
		</div>

	
	
	</div><!-- end parent row -->


</div>

<div class="result-row">
	<div class="row-fluid">
		<div class="span1 carrier-logo">
			<img src="<?php echo base_url(); ?>assets/img/carriers/maersk.svg" width="64px" height="64px">
		</div>
		<div class="span9 rate-body">
			<div class="rate-heading">
				<span id="origin-city" class="primary-city">Atlanta, GA, US</span>&rarr;<span class="via-city">Savannah, GA, US</span>&rarr;<span id="destination-city"  class="primary-city">Shanghai, CN</span>
			</div>
			<div class="rate-subtext">
				<div class="span3">
					<span class="info">service type:</span>
						CY / CY
				</div>
				<div class="span3">
						<span class="info">commodity:</span>
							General Cargo
				</div>
				<div class="span3">
						<span class="info">shipment type:</span>
						FCL - 40
				</div>
			</div>
		</div><!-- end class rate-body -->
		<div class="span2 rate-price">
			<div id="sell-rate"><span class="info">sell:</span>$1700</div>
			<div id="buy-rate"><span class="info">base:</span>$1600</div>
			<div id="margin">
				<span class="info">margin:</span>$100</div>
			</div>
		</div>

	</div>
</div>

