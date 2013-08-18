
<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Port Groups</li>
</ul>  


	<label for="add-port-group">Add Port Group</label>
	<div>
	  <input id="new-port-group" type="text" placeholder="Port Group name" />
	</div>
	<button id="add-port-group"><i class="icon-plus-sign"></i></button>

  <label>Port Group</label>
  <select id="port-groups">
  	<option value="0" selected="true">----</option>
  </select>

	<ul id="ports-list">
	</ul>
  	<div id="port-entry">
		<input type="text" id="port-input" name="port-input" placeholder="Enter Port" />
		<button id="add-port-to-group" class="btn btn-mini"><i class="icon-plus-sign"></i>Add to Group</button>
	</div>



<script type="text/javascript" charset="utf-8">
	var contract_id = '<?php echo $contract->id; ?>';
</script>