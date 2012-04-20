<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js wrap" lang="es"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<?php if ($this->uri->rsegment (1) == 'home'): ?>
		<title><?php echo $page_title ?></title>
	<?php else: ?>
		<title><?php echo ((!isset ($page_title) OR empty ($page_title)) ? '' : $page_title . ' // '), lang ('app_title') ?></title>
	<?php endif ?>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<meta name="viewport" content="width=device-width,initial-scale=1">
	
	<!-- CSS concatenated and minified via ant build script-->
	<link rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('css/jquery-ui-1.8.16.custom.css'); ?>">
	<!-- end CSS-->
	<script src="<?php echo base_url('js/libs/modernizr-2.0.6.min.js') ?>"></script>

	<script type="text/javascript" src="http://use.typekit.com/dys2hgx.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>

<body id="<?php echo $this->body_id ?>" data-controller="<?php echo $this->uri->rsegment (1) ?>" data-action="<?php echo $this->uri->rsegment (2) ?>">
  <div id="container">
    <header>
  		<?php
  		  if ($this->session->userdata ('so_guerrero_id'))
  		    $this->load->view('social/includes/inc.header.php');
  		  else
  		    $this->load->view('includes/inc.header.php');
  		?>
    </header>

	<?php if (isset ($show_map)): ?>
		<div id="map_canvas"></div>
	<?php endif; ?>

    <div id="main" role="main" class="<?php echo $this->body_id == 'home' || $this->body_id == 'power' ? '' : 'wrapper ' ?>clearfix">
      <?php if (isset ($main_content)) $this->load->view($main_content); ?>
    </div> <!--! end of #main.wrapper -->
  </div> <!--! end of #container -->
  
  <?php if ($page_title	!= 'Mapa de Luz'): ?>
  <footer>
    <?php $this->load->view('includes/inc.footer.php') ?>
  </footer>
  <?php endif ?>
  
  <?php $this->load->view('social/includes/inc.rank_lightbox.php') ?>
  
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&language=es"></script>
  <script type="text/javascript" src="<?php echo base_url('js/libs/infobox.js') ?>"></script>
  <script type="text/javascript">window.jQuery || document.write('<script src="<?php echo base_url('js/libs/jquery-1.7.1.min.js') ?>"><\/script>')</script>

	<!-- scripts concatenated and minified via ant build script-->
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery-ui-1.8.16.custom.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery-ui-datepicker-es.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery.ui.widget.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery.ui.core.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery.ui.position.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/mylibs/jquery.ui.autocomplete.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/libs/swfobject.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/plugins.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('js/script.js') ?>"></script>
	<!-- end scripts-->
	
	<!-- script tag for dynamic values from PHP -->
	<script type="text/javascript">
		PHPVARS = {
			base_url: '<?php echo base_url(); ?>',
			site_url: '<?php echo site_url(); ?>/'
		};
	</script>
	<!-- end PHP script tag -->
	
	<!-- function to get the dynamic URL for the Flash Light -->
	<script type="text/javascript">
		function webURL() { return PHPVARS.site_url; }
	</script>
  
  <!-- Facebook Share -->
  <div id="fb-root"></div>
  <script type="text/javascript" src="//connect.facebook.net/en_US/all.js"></script>
  <script type="text/javascript">
	  (function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=308942499144483";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
  </script>
	
	
	<!-- Google Analytics -->
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
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script type="text/javascript">window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
</body>
</html>
