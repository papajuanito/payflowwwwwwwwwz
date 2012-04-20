<!-- power_template.php -->
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js wrap" lang="es"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo ((!isset ($page_title) OR empty ($page_title)) ? '' : $page_title . ' // '), lang ('app_title') ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<!-- CSS concatenated and minified via ant build script-->
	<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
	<link type="text/css" href="<?php echo base_url ('css/admin.css?v=3') ?>" rel="stylesheet" />

	<!-- end CSS-->
	
	<script src="<?php echo base_url('js/libs/modernizr-2.0.6.min.js') ?>"></script>
	<script type="text/javascript" src="http://use.typekit.com/dys2hgx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['annotatedtimeline']});
    </script> 
</head>

<body id="<?php echo $this->body_id ?>" data-controller="<?php echo $this->uri->rsegment (1) ?>" data-action="<?php echo $this->uri->rsegment (2) ?>">

	<?php $this->load->view('power/inc_header', $header_data); ?> <!-- Header -->

  	<div id="content">
	    <?php if (isset ($main_content)) $this->load->view($main_content); ?>
    </div><!-- power_container -->
    
    
	<?php $this->load->view('power/inc_footer'); ?> <!-- Footer -->

  	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  	<script>window.jQuery || document.write('<script src="<?php echo base_url('js/libs/jquery-1.7.1.min.js') ?>"><\/script>')</script>

	<!-- scripts concatenated and minified via ant build script-->
	<script defer src="<?php echo base_url('js/plugins.js') ?>"></script>
	<script defer src="<?php echo base_url('js/script.js') ?>"></script>
	<!-- end scripts-->
	
	<!-- script tag for dynamic values from PHP -->
	<script type="text/javascript">
		PHPVARS = {
			base_url: '<?php echo base_url() ?>',
			site_url: '<?php echo site_url() ?>/'
		};
	</script>
	<!-- end PHP script tag -->
  
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-28364077-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->
</body>
</html>
