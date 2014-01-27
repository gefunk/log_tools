
<div class="row">
	<div id="assign" class="span12">
		<?php echo form_open('admin/contract/assign_document'); ?>
			
		<h2>Assign to Contract</h2>
		<div id="customer-select">
			<label>Customer:</label>
			<select id="customer" name="customer">
				<option value="0" selected="true"> ---- Please Select ---- </option>
			</select>
		</div>
		<div id="contract-select">
			<label>Contract:</label>
			<select id="contract" name="contract">
				<option value="0" selected="true"> ---- Please Select ---- </option>
			</select>
		</div>
		<input type="hidden" name="doc_id" value="<?php echo $doc_id; ?>" />
 		<input type="submit" class="btn btn-primary" value="Assign"/>
		</form>
	</div>
	
	<div id="doc-images" class="span12">
		
	</div>
</div>

<script type="text/javascript">
	var site_url = "<?php echo site_url(); ?>";
	var docId = "<?php echo $doc_id; ?>";
	var totalPages = "<?php echo $total_pages ?>";
</script>
