<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Your Password has been reset</title>
  </head>
  <body>
  	<h1>Your Password has been reset</h1>
  	<p>
  		Hey <?php echo $name; ?> Your password has been reset by <?php echo $requesting_user; ?>.<br/> Please use the following password to login: 
  		<span style="font-size: 200%; font-weight: bold;"><?php echo $password; ?></span>
  	</p>
  	<div>
  		Please login and change your password
  	</div>
  </body>
</html>