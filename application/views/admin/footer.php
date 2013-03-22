		</div> <!-- end of the container div -->
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

		<?php
		// way to pass in javascript to append to end of document
		if(isset($scripts)){
			foreach($scripts as $script):
		?>
			<script src="<?php echo base_url(); ?>assets/js/<?php echo $script; ?>"></script>
		<?php 
			endforeach; 
		}?>
	</body>
</html>