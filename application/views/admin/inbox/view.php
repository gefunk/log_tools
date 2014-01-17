<div class="row-fluid">
	<div class="span12">
		<div class="span5">
			<input type="file" name="contract-file" accept="application/pdf"/>
		</div>
		<div class="span7">
			<div id="file-upload-messages">
				<div class="progress">
  					<div class="bar" style="width: 0%;"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="span12">
		<table id="uploaded-files" class="table">
			<tr>
				<th>File</th>
				<th>Upload Date</th>
				<th>Status</th>
			</tr>
			<?php 
				foreach($docs as $doc):
					
			?>
			<tr id="<?php echo (string) $doc["_id"]; ?>">
				<td><?php echo $doc["file_name"]; ?></td>
				<td><?php echo date('m/d/Y h:i:s', $doc["date"]->sec); ?></td>
				<td>
				<?php if(isset($doc['progress'])): ?>
					<?php 
						$status = $doc['progress']['status'];
						$bar_class = "progress-info";
					?>
					<div><?php echo $status; ?></div>
					<div class="progress <?php echo $bar_class; ?>">
  						<div class="bar" 
  						style="width: <?php echo $doc['progress']['percent']; ?> %;"></div>
					</div>
				<?php endif; ?>
				</td>
			</tr>
			<?php
				endforeach;
			?>
		</table>
	</div>
</div>