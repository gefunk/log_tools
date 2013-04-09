<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title ?> - Amfitir Admin Managment</title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
		<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome-ie7.min.css">
		<![endif]-->
		<?php
		// way to pass in javascript to append to end of document
		if(isset($page_css)){
			foreach($page_css as $css):
		?>
		<link href="<?php echo base_url(); ?>assets/css/<?php echo $css; ?>" rel="stylesheet" />
		<?php 
			endforeach; 
		}?>
		<script type="text/javascript" charset="utf-8">
			var site_url = "<?php echo site_url(); ?>";
			var base_url = "<?php echo base_url(); ?>";
		</script>
	</head>
	<body>
		<div class="navbar navbar-static-top">
		  <div class="navbar-inner">
		    <a class="brand" href="#">Amfitir Contract Management Administration</a>
		    <ul class="nav">
		      <li class="dropdown">
				 <a href="<?php echo site_url(); ?>/admin/customer" class="active">
					Customers
				 </a>
			  </li>
		      <li><a href="<?php echo site_url(); ?>/admin/contract">Contracts</a></li>
		      <li><a href="#">Currencies</a></li>
		    </ul>
		  </div>
		</div>
		<div class="container-fluid">

			<div id="messages" class="row-fluid">
				<div class="span12">
					<?php echo validation_errors('<div class="alert alert-error">', '</div>'); ?>
					<?php if ( isset($messages) ) {?>
						<?php foreach($messages as $message):
						 	if($message['type'] == "success") { ?>
								<div class="alert alert-success">
									<?php echo $message['body']; ?>
								</div>
							<?php } ?>
						<?php endforeach; ?>
					<?php } // end if isset messages ?>
				</div>
			</div>

			<div class="row-fluid">