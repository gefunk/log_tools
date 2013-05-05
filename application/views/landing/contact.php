<!DOCTYPE html>
<html lang="en">
<head>
    <title>Amfitir - Contact</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Amfitir - Ocean Contract Management" />
    <meta name="author" content="Amfitir.com">

    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/landing/landing.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/landing/contact.css" rel="stylesheet">
        
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Delius+Swash+Caps" rel="stylesheet" type="text/css">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	<!-- font awesome -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome.min.css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome/font-awesome-ie7.min.css">
	<![endif]-->

</head>
<body>
<div class="container">
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="container">
                <a href="/" class="brand">Amfitir</a>
                    <ul class="nav">
                        <li><a href="<?php echo site_url(); ?>">Home</a></li>
                        <li class="active"><a href="<?php echo site_url('contact'); ?>">Contact</a></li>
                    </ul>
            </div>
        </div>
    </nav>
	<div class="row">
        <div class="span12 box drop-shadow">
			<h1 class="motto">Contact <strong>Us!</strong></h1>
            <form id="contact-us" action="<?php echo site_url('welcome/save_contact') ?>" method="POST">
				<fieldset>
					<legend>All the good Stuff!</legend>
					
					<label>Your name</label>
					<div class="input-prepend">
						<span class="add-on">@</span><input name="name" type="text" placeholder="Type your name...">
					</div>
					<span class="help-block">First and Last would really help.</span>
					
					<label>Your Phone Number</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-phone"></i></span><input name="phone" type="text" placeholder="Type your phone...">
					</div>
					<span class="help-block">Please enter your area code first</span>
					
					<label>Your Email address</label>
					<div class="input-prepend">
						<span class="add-on"><i class="icon-envelope"></i></span><input name="email" type="text" placeholder="Type your email..." />
					</div>
					<span class="help-block">We really hate spammers...and we promise never to spam!</span>
					
					<label>A Message</label>
					<textarea name="message" placeholder="We would love to hear from you!"></textarea>
					
					
				</fieldset>
				 <button type="submit" class="signup pull-right">Send<i class="icon-share-alt"></i></button>
			</form>
        </div>
    </div>
</div><!-- end container -->



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
	
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		

</body>
</html>
