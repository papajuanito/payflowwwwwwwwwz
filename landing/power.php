<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
  
  <!-- YUI Reset, Font -->
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css">

	<style type="text/css">
	h2
	{
    display: block;
    text-align: center;
	}
	#main
	{
    width: 500px;
    margin: 140px auto;
    font-family: helvetica neue, helvetica, arial, sans-serif;
    font-weight: lighter;
    font-size: 1.5em;
	}
	strong
	{
	 font-weight: bold;
	}
	</style>

</head>
<body>
<?php

require_once('inc.config.php');
  $cc = $mysqli->query("SELECT COUNT(id) AS total FROM lp_emails_list");
  $c = $cc->fetch_object();
	
?>
<div id="main">
<h2>Total a la fecha (<?php echo date('m-d-Y', time()) ?>): <strong><?php echo $c->total ?></strong></h2>
</div>
</body>
</html>