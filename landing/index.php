<?php require_once('inc.config.php'); ?>
<?php

  $placeholder = 'Ingresar correo electrónico…';

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">

	<title><?php echo TITLE ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
  
  <!-- YUI Reset, Font -->
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css">
  <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css">

	<link rel="stylesheet" href="skin/default.css">

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20142709-10']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<script type="text/javascript" src="http://use.typekit.com/dys2hgx.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

</head>
<body>
  <div id="main">
  
    <div id="simple_stuff">&nbsp;</div>
    <div id="video_container">
			<iframe src="http://player.vimeo.com/video/32279257?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" width="590" height="360" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
		</div>
		<h1>Contigo Comienza</h1>
		<p>La acción está por comenzar. ¡Espéralo pronto!</p>
    <input id="email" name="email" type="text" value="Ingresar correo electrónico…" />
    <input id="cta" name="" type="submit" value="enviar" />
    
    <div id="thanks"></div>
    
  </div>
  
  <div id="tao">
  </div>  
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready( function(){
  
  $('#email').focus( function(){
    if( $(this).val() == '<?php echo $placeholder ?>')
    {
      $(this).val('');
    }
  });
  
  $('#email').blur( function(){
    if( $(this).val() == '')
    {
      $(this).val('<?php echo $placeholder ?>');
    }
  });
  
  
  $('#cta').click( function(){
    
    if( $('#email').val() == '' || $('#email').val() == '<?php echo $placeholder ?>')
    {
      alert('Por favor, escriba su correo electrónico.');    
    }
    else
    {
      $.ajax({
        url: 'ajax.save_email.php',
        data: 'email='+$('#email').val(),
        success: function(res)
        {
          $('#video_container,#simple_stuff, #email, #cta, #main h1, #main p').fadeOut();
          $('#video_container').remove();
          $('#thanks').fadeIn('slow');
        }
      });
    }
    
    
  });      
    
});
</script>
</body>
</html>