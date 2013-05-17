<!DOCTYPE html>
<html class="login-bg">
<head>
	<title>Amfitir - Sign in</title>
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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/compiled/signin.css" type="text/css" media="screen" />

    <!-- open sans font -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
	
    <div class="row-fluid login-wrapper">
        <img class="logo" src="<?php echo base_url(); ?>assets/img/logo-white.png">

        <div class="span4 box">
			<form action="<?php echo site_url('main/login_user'); ?>" method="post" accept-charset="utf-8">
	            <div class="content-wrap">
	                <h6>Log in - <?php echo $customer_name; ?></h6>
	                <input class="span12" type="text" placeholder="E-mail address">
	                <input class="span12" type="password" placeholder="Your password">
	                <a href="#" class="forgot">Forgot password?</a>
	                <div class="remember">
	                    <input id="remember-me" type="checkbox">
	                    <label for="remember-me">Remember me</label>
	                </div>
	                <input type="submit" class="btn-glow primary login" value="Log in">
	            </div>
			</form>
        </div>

        <div class="span4 no-account">
            <p>Don't have an account?</p>
            <a href="<?php echo site_url('main/register'); ?>">Sign up</a>
        </div>
    </div>

	<!-- scripts -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/theme.js"></script>

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // pick a random background on load
			var bgs = Array("landscape.jpg", "blueish.jpg", "7.jpg", "8.jpg", "9.jpg", "10.jpg", "11.jpg");
			var bg = bgs[Math.floor(Math.random()*bgs.length)];
            $("html").css("background-image", "url('<?php echo base_url(); ?>assets/img/bgs/" + bg + "')");
            

        });
    </script>



</body>
</html>