
<div id="query-selection" class="well">
	
	<div class="row-fluid">
		<div class="span12">
			<div class="span4">
				<label for="origin">Origin</label>
				<input type="hidden" class="query-input" name="origin" id="origin" placeholder="Origin city"  style="width: 226px;"/>
			</div>
			<div class="span4">
				<label for="Destination">Destination</label>
				<input type="hidden" class="query-input" name="destination" id="destination" placeholder="Destination city" style="width:226px;">			
			</div>
			<div class="span4">
				<button type="button" class="btn btn-primary button-input"><i class="icon-search"></i><span>Find Rates</span></button>
				<button id="filter" type="button" class="btn button-input"><i class="icon-filter"></i><span>Filter</span></button>			
			</div>
		</div>
	</div>

	<div id="filter-input">
		<div class="row-fluid">
			<div class="span12">
				Filter(s):
			</div>
		</div>
		<div class="row-fluid">
			<div class="span4">
				<label for="limit-origin">Origin Port:</label>
				<input type="hidden" name="limit-origin" id="limit-origin" placeholder="Limit Port of Origin to" style="width:226px" />
			</div>
			<div class="span4">
				<label for="limit-destination">Destination Port</label>
				<input type="hidden" name="limit-destination" id="limit-destination" placeholder="Limit Port of Destination to" style="width:226px"/>
			</div>
		
			
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<label for="container_size">Container Size</label>
				<select id="container_size" name="container_size" placeholder="Container Size">
					<option value="0" style="color: gray;">-- Limit Container Size --</option>
					<option value="Dry">20</option>
					<option value="Dry">40</option>
				</select>
			</div>
			<div class="span4">
				<label for="container_type">Container Type</label>
				<select id="container_type" name="container_type" placeholder="Container Type">
					<option value="0" style="color: gray;">-- Limit Container Type --</option>
					<option value="Dry">Dry</option>
				</select>
			</div>
			<div class="span4">
				<label for="dangerous_goods">Dangerous Goods</label>
				<select placeholder="Dangerous Goods">
					<option value="0" style="color: gray;">-- Dangerous Goods --</option>
					<option value="cycy">CY/CY</option>
					<option value="rycy">RY/CY</option>
				</select>
			</div>
		</div>
		
		<div class="row-fluid">
			
			

		</div>
	</div>
	
</div><!-- query input selection -->

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

