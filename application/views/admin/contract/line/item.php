<?php 
	/**
	 * 
    public $id;
    public $origin;
    public $origin_type;
    public $destination;
    public $destination_type;
    public $cargo;
    public $effective;
    public $expires;
    public $currency;
    public $service;
    public $deleted;
    public $containers;
    public $surcharges;
    public $tariffs;
    public $contract;
	 */
?>

<div class="row">
  <table class="table">
      <tr>
      	<th>Origin</th>
      	<th>Destination</th>
      	<th>Cargo</th>
      	<?php 
      		foreach($container_type as $type){
      			?><th><?php echo $type; ?></th><?php
      		}
      	?>
      </tr>
      <tr>
      	<td><?php echo origin; ?></td>
      	<td><?php echo destination; ?></td>
      	<td><?php echo cargo; ?></td>
      	<?php
      		foreach($container_prices as $container){
      			?><td><?php echo $container ?></td><?php
      		} 
      	?>
      </tr>
  </table>
</div>