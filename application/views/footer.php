		
			</div> <!-- end of the container div -->
		</div><!-- end content div -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ejs/ejs_fulljslint.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ejs/ejs.js"></script>
		
			<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/typeahead.min.js"></script>
		
		<script src="http://twitter.github.com/hogan.js/builds/2.0.0/hogan-2.0.0.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/global.js"></script>

		<?php
		// way to pass in javascript to append to end of document
		if(isset($scripts)){
			foreach($scripts as $script):
		?>
			<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/<?php echo $script; ?>"></script>
		<?php 
			endforeach; 
		}?>
		<?php 
		if (defined('ENVIRONMENT') && ENVIRONMENT == 'production')
		{
		?>
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-39338064-1']);
		  _gaq.push(['_setDomainName', 'amfitir.com']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
		<?php 
		} // end if environment 
		?>
	</body>
</html>