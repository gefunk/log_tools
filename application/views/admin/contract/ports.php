<div class="row">
	<label for="add-port-group">Add Port Group</label>
	<div>
	  <input id="new-port-group" type="text" placeholder="Port Group name" />
	</div>
	<button id="add-port-group"><i class="icon-plus-sign"></i></button>
</div>

<div class="row">
  <label>Port Group</label>
  <select id="port-groups">
  	
  </select>
</div>

<div class="row">
	<ul id="ports-list">
	</ul>
  	<div id="port-entry">
		<input type="text" id="port-input" name="port-input" placeholder="Enter Port" />
	</div>
</div>



<script type="text/javascript" charset="utf-8">
	var contract_id = '<?php echo $contract_id; ?>';
</script>