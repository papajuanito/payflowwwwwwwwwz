<!doctype html>  
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]--> 
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]--> 
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]--> 
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]--> 
<head>
  	<meta charset="utf-8"> 
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
 
  	<title>&Aacute;rea Administrava - Guerreros de Luz</title> 
  	
  	<meta name="description" content=""> 
  	<meta name="author" content=""> 
 
  	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  	<!--<link rel="shortcut icon" href="images/favicon.ico">
  	<link rel="apple-touch-icon" href="images/ios/apple-touch-icon.png">  -->
 	
 	<link href="//fonts.googleapis.com/css?family=Droid+Sans:regular,bold" rel="stylesheet" type="text/css" >
  	<!--link rel="stylesheet" href="css/default.css?v=1"-->
  	<link rel="stylesheet" href="<?php echo base_url('css/admin.css'); ?>">
  	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>
</head>

<body style="background: #005FAE;" >
	
	<div id="login">
		<h1><a href="#">Guerreros de Luz</a></h1>
		
		<?php if(!empty($e)){ ?>
			<p style="padding: 15px; color: red;">&iexcl;Error! Trate nuevamente.</p>
		<?php } ?>
		<form action="<?php echo site_url('power/check_login') ?>" method="post">
			<label>Usuario</label>
			<input type="text" name="username" value="" size="" />
			<label>Contrase&ntilde;a</label>
			<input type="password" name="password" value="" size="" />
			
			<input type="submit" value="Acceder" />
		</form>
			

	</div>

</body> 
</html>