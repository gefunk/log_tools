<div id="search">
	<div class="row-fluid">
		<div class="span12">
			<h4> Search <small class="hidden-phone">Enter an origin and destination</small></h4>
		</div>
		<div id="route-search" class="span12">
			<div class="span6">
				<input id="origin" placeholder="Origin" />
			</div>
			<div class="span6">
				<input id="destination" placeholder="Destination" />
			</div>
		</div>
		<div id="route-dates" class="span12">
			<div class="input-prepend">
				<span class="add-on"><i class="icon-calendar"></i></span>
				<input id="ship_date" span="4" type="text" placeholder="Ship Date" />
			</div>
		</div>

		<div class="span12">
			<div class="span2">
				<button id="press" class="btn-flat btn-primary">
					Search
				</button>
			</div>
		</div>

		<div class="span12">
			<a href="#">More Options...</a>
		</div>
	</div>
</div>

<div id="results-group">
	
	
	<div id="filters" class="row-fluid">
		<div class='span11'>
			
				<div id="carrier" class="filter" data-filter-dropdown="carrier-drop">
				Any Carrier
				<i class="icon-caret-down"></i>
				</div>
				<div id="container" class="filter" data-filter-dropdown="container-drop">
				Any Container
				<i class="icon-caret-down"></i>
				</div>	
			
			
			
		</div>
	</div>
	
	<div class="separator">
		&nbsp;
	</div>
	
	<div class="row-fluid">
		<div id="results-header" class="span6">
			<span class="location origin">Savannah</span>
			<span class="icon-long-arrow-right"></span>
			<span class="location destination">Shanghai</span>
			<span class="date"><small>Thu, Aug 18, 2013</small></span>
		</div>
		<div id="sort" class="span2 filter pull-right">
				Sort by Price
				<i class="icon-caret-down"></i>
		</div>

	</div>

	

	<div class="row-fluid result-row">
		<div class="span2">
			<img class="carrier-img" src="<?php echo base_url(); ?>assets/img/carriers/cma_cgm.jpg" />
		</div>

		<div class="span2">
			<p>
				40 foot container
			</p>
		</div>
		<div class="span2">
			<p>
				General Cargo
			</p>
		</div>
		<div class="span2 hidden-phone"></div>
		<div class="span3" style="text-align: right;">
			<table id="prices">
				<tr>
					<td><span class="label label-success">sell</span></td>
					<td>$ 2300.00</td>
				</tr>
				<tr class="hidden-phone">
					<td><span class="label">cost</span></td>
					<td>$ 2000.00</td>
				</tr>
				<tr class="hidden-phone">
					<td><span class="label label-info">margin</span></td>
					<td>$ 300.00</td>
				</tr>
			</table>
		</div>
		<div class="span10">
			<ul id="legs">
				<li>
					<h4>
						<i class='icon-truck'></i>
						Atlanta, GA, US <i class="icon-long-arrow-right"></i> Savannah, GA, US
					</h4>
					
					<div>
						OOCL W18913 Arbitrary
						<a href="#">Details...</a>
					</div>
				</li>
				<li>
					<h4>
						<i class='icon-anchor'></i>
						Savannah, GA, US <i class="icon-long-arrow-right"></i> Shanghai, CN
					</h4>
					<div>
						OOCL W18913 Ocean
						<a href="#">Details...</a>
					</div>
				</li>
				<li>
					<h4>
						<i class='icon-truck'></i>
						Shanghai, CN <i class="icon-long-arrow-right"></i> Quangzhou, CN
					</h4>
					<div>
						OOCL W18913 Arbitrary
						<a href="#">Details...</a>
					</div>
				</li>
			</ul>
		</div>

	</div>
	<div class="row-fluid result-row">
		<div class="span1">
			<img class="carrier-img" src="<?php echo base_url(); ?>assets/img/carriers/anl.jpg" />
		</div>
	</div>
	<div class="row-fluid result-row">
		<div class="span1">
			<img class="carrier-img" src="<?php echo base_url(); ?>assets/img/carriers/maersk.svg" />
		</div>
	</div>
	<div class="row-fluid result-row">
		<div class="span1">
			<img class="carrier-img" src="<?php echo base_url(); ?>assets/img/carriers/hapag.png" />
		</div>
	</div>
	<div class="row-fluid result-row">
		<div class="span1">
			<img class="carrier-img" src="<?php echo base_url(); ?>assets/img/carriers/zim.jpg" />
		</div>
	</div>
</div>


<div id="carrier-drop" class="pop-dialog filter-dropdown" data-toggle="off">
	<div class="pointer">
		<div class="arrow"></div>
		<div class="arrow_border"></div>
	</div>
	<div class="body">
	  <div class="menu">
		<div class="header">
		  <a class="item" href="#">Any Carrier</a>
		</div>
		
		<label class="checkbox item">
			<input type="checkbox" />
			Maersk
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				ANL
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				Hanjin
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				Hapag-Lloyd
		</label>
	  </div>
	</div>
</div>


<div id="container-drop" class="pop-dialog filter-dropdown" data-toggle="off">
	<div class="pointer">
		<div class="arrow"></div>
		<div class="arrow_border"></div>
	</div>
	<div class="body">
	  <div class="menu">
		<div class="header">
		  <a class="item" href="#">Any Container</a>
		</div>
		<label class="checkbox item">
			<input type="checkbox" />
			20 Foot
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				40 Foot
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				40 Foot Open Top
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				45 Foot
		</label>
		<label class="checkbox item">
				<input type="checkbox" />
				45 Foot Open Top
		</label>
	  </div>
	</div>
</div>