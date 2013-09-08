<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title ?> - Amfitir</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	    <!-- bootstrap -->
	    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.css" rel="stylesheet" />
	    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
	    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

	    <!-- libraries -->
	    <link href="<?php echo base_url(); ?>assets/css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
		<!-- font awesome -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome.min.css">
		<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome-ie7.min.css">
		<![endif]-->
		
	    <!-- global styles -->
	    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/compiled/layout.css">
	    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/compiled/elements.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/compiled/layout.css">
	    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/icons.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/lib/bootstrap.datepicker.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/typeaheadjs-bootstrap.css">
		
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/global.css">

	    <!-- this page specific styles -->
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/compiled/index.css" type="text/css" media="screen" />

	    <!-- open sans font -->
	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

	    <!-- lato font -->
	    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

	    <!--[if lt IE 9]>
	      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->

		<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/img/favicon.ico" />
	
		<!-- page specific css -->
		<?php
		// way to pass in page specific css from php
		if(isset($page_css)){
			foreach($page_css as $css):
		?>
		<link href="<?php echo base_url(); ?>assets/css/<?php echo $css; ?>" rel="stylesheet" />
		<?php 
			endforeach; 
		}?>
		

		<script type="text/javascript" charset="utf-8">
			// script global values available in every page
			var site_url = "<?php echo site_url(); ?>";
			var base_url = "<?php echo base_url(); ?>";
		</script>
	</head>
	<body>
		
		<div id="overlay"></div>
		
		
		<!-- navbar -->
	    <div class="navbar navbar-inverse">
	        <div class="navbar-inner">
	            <a class="brand" href="<?php echo site_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/nav-logo.png">
	            		<span class="hidden-phone">Amfitir Rate Manager</span>
	            	</a>

	            <!-- shows same menu as sidebar but for mobile devices -->
	            <button type="button" class="btn btn-navbar visible-phone" data-toggle="collapse" data-target=".nav-collapse">
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>            
	            <div class="nav-collapse collapse visible-phone mobile-menu">
	                <ul id="phone-nav" class="nav">
	                    <li id="rates"><a href="<?php echo site_url(); ?>/main">Rates</a></li>
	                    <li id="contracts"><a href="<?php echo site_url(); ?>/contract">Contracts</a></li>
	                    <li id="users"><a href="<?php echo site_url(); ?>/users">Users</a></li>
	                    <li><a href="form-showcase.html">Forms</a></li>
	                    <li><a href="gallery.html">Gallery</a></li>
	                </ul>
	            </div>
	            <!-- end navbar for mobile devices -->

	            <ul class="nav pull-right">
	                <li class="hidden-phone">
	                    <input class="search" type="text" />
	                </li>
	                <li class="dropdown">
	                    <a href="#" class="dropdown-toggle hidden-phone" data-toggle="dropdown">
	                        Your account
	                        <b class="caret"></b>
	                    </a>
	                    <ul class="dropdown-menu">
	                        <li><a href="personal-info.html">Personal info</a></li>
	                        <li><a href="#">Account settings</a></li>
	                        <li><a href="#">Billing</a></li>
	                        <li><a href="#">Export your data</a></li>
	                        <li><a href="#">Send feedback</a></li>
	                    </ul>
	                </li>
	                <li class="settings">
	                    <a href="personal-info.html" role="button">
	                        <span class="navbar_icon"></span>
	                    </a>
	                </li>
	                <li id="fat-menu" class="dropdown">
	                    <a href="<?php echo site_url(); ?>/logout" role="button" class="logout">
	                        <span class="navbar_icon"></span>
	                    </a>
	                </li>
	            </ul>            
	        </div>
	    </div>
	    <!-- end navbar -->

	    <!-- sidebar -->
	    <div id="sidebar-nav" class="hidden-phone">
	        <ul id="dashboard-menu">
	            <li id='rates'>
	                <a class="tab1" href="<?php echo site_url().((defined('ENVIRONMENT') && ENVIRONMENT == 'development') ? '/main' : ''); ?>">
	                    <i class="sidebar-forms"></i>
	                    <span>Rates</span>
	                </a>
	            </li>       
				<li id='contracts'>
	                <a class="tab2" href="<?php echo site_url(); ?>/contract">
	                    <i class="sidebar-tables"></i>
	                    <span>Contracts</span>
	                </a>
	            </li>     
	            <li id='analytics'>
	                <a class="tab2" href="chart-showcase.html">
	                    <i class="sidebar-charts"></i>
	                    <span>Analytics</span>
	                </a>
	            </li>
	            <li id="users">
	                <a class="tab2" href="<?php echo site_url(); ?>/users">
	                    <i class="icon-group"></i>
	                    <span>Users</span>
	                </a>
	            </li>
	            <li class="">
	                <a class="tab9" href="personal-info.html">
	                    <i class="sidebar-gear"></i>
	                    <span>Settings</span>
	                </a>
	            </li>
	            <li class="">
	                <a class="tab10" href="<?php echo site_url(); ?>/logout">
	                    <i class="sidebar-logout"></i>
	                    <span>Logout</span>
	                </a>
	            </li>
	        </ul>
	    </div>
	    <!-- end sidebar -->


		<!-- main container -->
	    <div class="content">

	        <div class="container-fluid">
				