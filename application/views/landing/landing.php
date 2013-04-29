<!DOCTYPE html>
<html lang="en">
<head>
    <title>Amfitir - Ocean Contract Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Amfitir - Ocean Contract Management" />
    <meta name="author" content="Amfitir.com">

    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/landing/landing.css" rel="stylesheet">
        
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700,300" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Delius+Swash+Caps" rel="stylesheet" type="text/css">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>
<div class="container">
    <nav class="navbar">
        <div class="navbar-inner">
            <div class="container">
                <a href="/" class="brand">Amfitir</a>
                    <ul class="nav">
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="<?php echo site_url().'/contact'; ?>">Contact</a></li>
                    </ul>
            </div>
        </div>
    </nav>
	<?php if(isset($alert)){ ?>
	<div class="row alerts">
		<?php if($alert == "contact"){ ?>
		<div "thanks-contact" class="offset2 span8 alert alert-success">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <h4>Thank you for Contacting Us!</h4>
		  <p>We will get back to you <strong>ASAP</strong> - that is as soon as possible!</p>
		</div>
		<?php } elseif($alert == "newsletter") { ?>
		<div id="thanks-news" class="offset2 span8 alert alert-success">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <h4>Thank you for signing up to the newsletter!</h4>
		  <p>We hope you enjoy !</p>
		</div>
		<?php } // end alert == contact or newsletter ?>
	</div>
	<?php } // end isset alert ?>
    <div class="row">
        <div class="feature span12">
            <h1 class="motto">Ocean Contract Management is <strong>Amfitir</strong>.</h1>
            <h2 class="subheader">Be efficient with your rates!</h2>
            <img id="feature-screenshot" src="<?php echo base_url(); ?>assets/img/pics/main.png" alt="screenshot" />
        </div>
    </div>
    <div class="row">
        <div class="span12 graded center">
            <p>Amfitir converts your ocean contracts from convoluted paper documents to an easy to use, searchable rate interface.</p>
        </div>
    </div>
</div>
<div class="container">
    <h2><strong>Features</strong> you'll love.</h2>
    <h3 class="subheader">We know what makes you successful.</h3>
    <div class="row">
        <div class="span12">
        <ul class="thumbnails">
            <li class="span3">
                <div class="thumbnail">
                    <span>Instant Quotes</span>
                    <img src="<?php echo base_url(); ?>assets/img/pics/quote-icon.png" width="260" height="180" alt="">
                    
                </div>
            </li>
            <li class="span3">
                <div class="thumbnail">
                    <span>Analytics</span>
                    <img src="<?php echo base_url(); ?>assets/img/pics/analytics-icon.png" width="260" height="180" alt="">
                </a>
            </li>
            <li class="span3">
                <div class="thumbnail">
                    <span>Integration</span>
                    <img src="<?php echo base_url(); ?>assets/img/pics/integrate-icon.png" width="260" height="180" alt="">
                </div>
            </li>
            <li class="span3">
                <div class="thumbnail">
                    <span>Cloud Infrastructure</span>
                    <img src="<?php echo base_url(); ?>assets/img/pics/cloud-icon.png" width="260" height="180" alt="">
                </div>
            </li>
        </ul>
        </div>
    </div>
    
    <div class="row">
        <div class="span12 box drop-shadow">
            <div class="row">
                <article class="span6 offset1">
                    <h3>World class Rate Engine.</h3>
                    <p>
						Our rate engine factors in all facets from all your contracts to provide you the best quote for your customer. The rate engine calculates in your rates in milliseconds allowing you to be responsive to your customer's needs. Our engine also keeps track of rate requests and allows you to gain visibility into your ocean contracts.
                    </p>
                </article>
                <div class="span4">
                    <img src="<?php echo base_url(); ?>assets/img/pics/rate-engine.png" alt="placeholder" />
                </div>
            </div>
            <div class="row">
                <div class="span4 offset1">
                    <img src="<?php echo base_url(); ?>assets/img/pics/responsive.png" alt="placeholder" />
                </div>
                <article class="span6">
                    <h3>Fully responsive design.</h3>
                    <p>Our platform works on all devices. From desktop computers to tablets to phones. We provide a fully responsive interface which allows you to quote from any where and any device. Our service is delivered from the cloud so you'll always be connected to your rating platform!</p>
                </article>
            </div>
            <div class="row">
                <article class="span6 offset1">
                    <h3>People Power.</h3>
                    <p>We hire the right people! Our people have experience at carriers and other large NVOCC's. You can be assured that your contract will be analyzed and translated by industry experts. We also have support staff off-shore to ensure quick turnaround times on contract amendments.
                    </p>
                </article>
                <div class="span4">
                    <img src="<?php echo base_url(); ?>assets/img/pics/peope-icon.png" alt="placeholder" />
                </div>
            </div>
			<div class="row">
                <div class="span4 offset1">
                    <img src="<?php echo base_url(); ?>assets/img/pics/flexible-icon.png" alt="placeholder" />
                </div>
                <article class="span6">
                    <h3>Flexible Platform.</h3>
                    <p>We are proud to have a platform that is extremely flexible to meet every one of our customer needs. Our platform is capable of maintaining profit margins by different levels of your business: by Contract, Customer, Shipping lane. We also have services that provide up to the second currency conversion built directly into the platform. You can create your own harmonized codes and tarriffs. All our settings are manageable directly via our Settings Interface. Amfitir also supports a variety of integration choices, into other ERP systems, CRM's  and Billing Systems.</p>
                </article>
            </div>
			 <div class="row">
	                <article class="span6 offset1">
	                    <h3>Speed and Security.</h3>
	                    <p>Our service is built on the fastest cloud interface in the world. Our service is engineered to provide incredibly quick response times by using the latest technology and bleeding edge internet techniques. You can be ensured that your rates will be secure in our system because we use <strong>Secure Services Layer (SSL)</strong> protocol to  keep your information secure. Your data will also be safe from data loss, because we keep a secure copy in a different geographic zone of all your information. With Amfitir, you can be assured you will never experience downtime.
	                    </p>
	                </article>
	                <div class="span4">
	                    <img src="<?php echo base_url(); ?>assets/img/pics/speed.png" alt="placeholder" />
	                </div>
	            </div>
			</div>
        </div>
    </div>
</div>    
<div class="container">

    <div class="row">
        <div class="span12 center graded">
            <a href="<?php echo site_url().'/contact'; ?>" class="signup">Contact Us</a>
        </div>
    </div>
</div>
<div class="container">
    <h2>Our Success is <strong>your success.</strong></h2>
    <div class="row">
        <div class="span12 box">
            <article class="span10 offset1">
            <p>Most Importantly! We want to work with you! At Amfitir we take a partnership approach to every client that we work with. We know that our success lies with your success. Our people are committed to helping you get the most out of your contracts.</p>
            <p>We will do everything possible to provide the best service for your business.</p>
            </article>
        </div>
    </div>
</div>
<div class="container">
    <h3 class="subheader">Keep in touch.</h3>
    <div class="row">
        <div class="span8">
            <h4>About us</h4>
            <p>Amfitir.com was founded in 2013 by <strong>R2 LLC</strong> to meet a need for efficient contract management for Ocean Transportation Intermediaries.</p>
            <p></p>
            
        </div>
        <div class="span4">
            
            <form class="form-horizontal" action="<?php echo site_url().'/welcome/save_newsletter'; ?>" method="POST">
                <h4>Sign up to the newsletter</h4>
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-envelope"></i></span><input name="email" type="text" id="inputIcon" class="span2" placeholder="Email address">
                </div>
                    
                <button class="btn">Sign up</button>
            </form>
            
        </div>
    </div>
    <div class="row footer">
        <div class="span12">
            <ul class="links">
                <li><a href="#"><img src="<?php echo base_url(); ?>assets/img/icons/twitter.png" alt="twitter" /></a></li>
                <li><a href="#"><img src="<?php echo base_url(); ?>assets/img/icons/facebook.png" alt="fb" /></a></li>
                <li><a href="#">Support</a></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Jobs</a></li>
                <li><a href="#">Contact</a></li>
            </ul>           
        </div>
    </div>
         
</div>



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
