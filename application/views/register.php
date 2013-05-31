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
				<div class="error">
					
				</div>
				<form action="<?php echo site_url('login/register_user'); ?>" method="POST" id="register-form">
	                <h6>Sign Up - Welcome <?php echo $this->session->userdata['customer_name']; ?></h6>
	                <input class="span12" type="text" class="required email" name="email" minlength="4" placeholder="E-mail address">
	                <input class="span12" type="password" id="password1" class="required password" name="password" placeholder="Password">
	                <input class="span12" type="password" class="required" equalTo="#password1" name="password2" placeholder="Confirm Password">
					<input class="span12" type="text" name="first_name" class="required" minlength="3" maxlength="40" placeholder="First Name">
					<input class="span12" type="text" name="last_name" class="required" minlength="3" maxlength="40" placeholder="Last Name">
					<input class="span12" type="text" name="phone_no" class="required phone" maxlength="14" placeholder="Phone Number">
	                <div class="action">
	                    <input type="submit" class="btn-glow primary signup submit" value="Sign up" />
	                </div>        
        		</form>
            </div>
        </div>

        <div class="span4 already">
            <p>Already have an account?</p>
            <a href="<?php echo site_url('main/signin'); ?>">Sign in</a>
        </div>
    </div>

	<!-- scripts -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/theme.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
	
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function(){
			
			$("div.error").hide();
			
			
			/** validate code **/
			$("#register-form").validate({
				debug: true
			});

			$("#register-form").submit(function(e){
				console.log($("#register-form").valid());
				if($("#register-form").valid()){
					this.submit();
					return true;
				}
				return false;
			})
		});
	</script>
	
</body>
</html>