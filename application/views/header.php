<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title ?> - Amfitir Contract Managment</title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Bootstrap -->
		<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" media="screen" />
		<link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/css/global.css" rel="stylesheet" />
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
		<div class="container-fluid">
			<div class="row-fluid">