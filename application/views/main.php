<div id="search">
	<div class="row-fluid">
		<div class="span12">
			<h4> Search <small>Enter an origin and destination</small></h4>
		</div>
		<div id="route-search" class="span12">
			<div class="span5">
				<input id="origin" placeholder="Origin"/>
			</div>
			<div class="span5">
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
	<div class="row-fluid">
		<div id="results-header" class="span6">
			<span class="location origin">Savannah</span>
			<span class="icon-long-arrow-right"></span>
			<span class="location destination">Shanghai</span>
			<span class="date"><small>Thu, Aug 18, 2013</small></span>
		</div>

	</div>

	<div class="row-fluid">
		<div class='span8'>
		<div id="sort">
			<div class="btn-group ">
				<a class="btn glow dropdown-toggle" data-toggle="dropdown" href="#"> Sort <span class="caret"></span> </a>
				<ul class="dropdown-menu">
					<li>
						<a href="#">Price</a>
					</li>
				</ul>
			</div>
		</div>
		<div id="carrier">
			<div class="btn-group ">
				<a class="btn glow dropdown-toggle" data-toggle="dropdown" href="#"> Any Carrier <span class="caret"></span> </a>
				<ul class="dropdown-menu">
					<li>
						<a href="#">Any Carrier</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="#">
							<label class="checkbox">
								<input type="checkbox" />
								Maersk
							</label>
						</a>
					</li>
					<li>
						
						<a href="#">
							<label class="checkbox">
								<input type="checkbox" />
								ANL
							</label>
						</a>
					</li>
				</ul>
			</div>
		</div>
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
						<i class='icon-plane'></i>
						Atlanta, GA, US <i class="icon-long-arrow-right"></i> Savannah, GA, US
					</h4>
					
					<div>
						OOCL W18913 Arbitrary
						<a href="#">Details...</a>
					</div>
				</li>
				<li>
					<h4>
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

