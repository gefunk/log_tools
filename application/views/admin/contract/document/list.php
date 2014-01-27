<ul class="breadcrumb">
	<li><a href="<?php echo site_url().'/admin/contract/all/'.$customer->_id; ?>">All Contracts</a><span class="divider">/</span></li>
	<li><a href="<?php echo site_url().'/admin/contract/manage/'.$contract->_id; ?>"><?php echo $contract->number; ?></a><span class="divider">/</span></li>
	<li class="active">Documents</li>
</ul>  

<?php foreach($documents as $document): ?>
<div class="span9">
	<div  id="doc-<?php echo $document->_id; ?>" class="doc">
		<div>
			<span class="label label-info">id: <?php echo $document->_id; ?></span>
			<span class="label label-success">upload: <?php echo date('D, d M Y h:i:s a',$document->date->sec); ?></span>
			<span class="label label-inverse"># of pages: <?php echo count($document->pages); ?></span>
			
		</div>
		<div class="doc-thumbnails" id="<?php echo $document->_id; ?>">
			
		</div>
		
		<div>
			<button class="btn btn-success add-doc-tag" data-doc-id="<?php echo $document->_id; ?>"><i class="icon-plus"></i></button>
			<input class="span2 doc-tag-input" type="text" placeholder="tags">	
		</div>
		
		
		<div class="doc-tags">
			<?php 
			if(isset($document->tags)): 
				foreach($document->tags as $tag): 
			?>
					<span class="doc-tag">
						<?php echo $tag ?>
						<i data-doc-id="<?php echo $document->_id; ?>" class="icon-remove delete-doc-tag"></i>	
					</span>
			<?php 
				endforeach; 
			endif;
			?>
		</div>
	</div>
</div>
<?php endforeach; ?>
