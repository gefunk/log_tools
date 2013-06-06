<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title><?php echo $title ?> - Amfitir</title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

	    <!-- this page specific styles -->
	    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/compiled/index.css" type="text/css" media="screen" />

	    <!-- open sans font -->
	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

	    <!-- lato font -->
	    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

	    <!--[if lt IE 9]>
	      <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->
	
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
		<!-- navbar -->
	    <div class="navbar navbar-inverse">
	        <div class="navbar-inner">
	            <a class="brand" href="index.html"><img src="<?php echo base_url(); ?>assets/img/nav-logo.png">Amfitir Rate Manager</a>

	            <!-- shows same menu as sidebar but for mobile devices -->
	            <button type="button" class="btn btn-navbar visible-phone" data-toggle="collapse" data-target=".nav-collapse">
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>            
	            <div class="nav-collapse collapse visible-phone mobile-menu">
	                <ul class="nav">
	                    <li class="active"><a href="index.html">Home</a></li>
	                    <li><a href="chart-showcase.html">Charts</a></li>
	                    <li><a href="user-list.html">Users</a></li>
	                    <li><a href="form-showcase.html">Forms</a></li>
	                    <li><a href="gallery.html">Gallery</a></li>
	                    <li><a href="icons.html">Icons</a></li>
	                    <li><a href="calendar.html">Calendar</a></li>
	                    <li><a href="tables.html">Tables</a></li>
	                    <li><a href="ui-elements.html">UI Elements</a></li>
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
	                    <a href="signin.html" role="button" class="logout">
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
	            <li class="active">
	                <div class="pointer">
	                    <div class="arrow"></div>
	                    <div class="arrow_border"></div>
	                </div>
	                <a class="tab1" href="index.html">
	                    <i class="sidebar-forms"></i>
	                    <span>Rates</span>
	                </a>
	            </li>       
				<li class="">
	                <a class="tab2" href="chart-showcase.html">
	                    <i class="sidebar-tables"></i>
	                    <span>Contracts</span>
	                </a>
	            </li>     
	            <li class="">
	                <a class="tab2" href="chart-showcase.html">
	                    <i class="sidebar-charts"></i>
	                    <span>Analytics</span>
	                </a>
	            </li>
	            <li class="">
	                <a class="tab2" href="user-list.html">
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
	                <a class="tab10" href="signin.html">
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
				<div class="row-fluid">