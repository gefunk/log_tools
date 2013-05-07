<!DOCTYPE html>
<html class="login-bg">
<head>
	<title>Amfitir Rate Manager - Register</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <!-- bootstrap -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet">

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/layout.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/elements.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/icons.css">

    <!-- libraries -->
    <!-- font awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome.min.css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome-ie7.min.css">
	<![endif]-->

    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/compiled/signup.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <div class="header">
        <img src="<?php echo base_url(); ?>assets/img/logo.png" class="logo" />
    </div>
    <div class="row-fluid login-wrapper">
        <!-- <img class="logo" src="img/logo-white.png"> -->

        <div class="box">
            <div class="content-wrap">
				<form action="register/<?php echo $customer_group ?>" method="POST">
                <h6>Sign Up</h6>
                <input class="span12" type="text" name="email" placeholder="E-mail address">
                <input class="span12" type="password" name="password" placeholder="Password">
                <input class="span12" type="password" placeholder="Confirm Password">
                <div class="action">
                    <a class="btn-glow primary signup">Sign up</a>
                </div>                
            </div>
        </div>

        <div class="span4 already">
            <p>Already have an account?</p>
            <a href="../signin/<?php echo $customer_id ?>">Sign in</a>
        </div>
    </div>

	<!-- scripts -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/theme.js"></script>
</body>
</html>