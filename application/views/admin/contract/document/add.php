
<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/document/manage/'.$contract->id; ?>">Document Manager</a><span class="divider">/</span></li>
	<li class="active">New Document</li>
</ul>  


<div id="upload-modal">
  <div class="modal-header">
    <h3>Upload Contract Document</h3>
  </div>
  <div class="modal-body">
    <?php echo form_open_multipart('admin/contract/upload'); ?>
		<div id="upload-message">
		  
		</div>
	  	<input name="contract-file" type="file" />
	  	<input name="customer_id" type="hidden" value="<?php echo $customer->id; ?>" />
	  	<input name="contract_id" type="hidden" value="<?php echo $contract->id; ?>" />
	  	
	  	
		<div id="upload-progress">
			<p>File Upload Progress</p>
		  	<div class="progress active">
  				<div class="bar" style="width: 0%;"></div>
			</div>  
		</div>
		
	</form>
  </div>
  <div id="upload-message" class="alert alert-info hide"></div>
  <div>
    <button id="upload-file" class="btn btn-primary">Upload</button>
  </div>
</div>

<script type="text/javascript" charset="utf-8">
	var contract_id = <?php echo $contract->id ?>;
</script>