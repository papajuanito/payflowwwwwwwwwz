	<div id="flash_light_container">
		<div id="flash_light">
			<img src="<?php echo base_url('img/home/bg_stats_map.png') ?>">
		</div>
	</div>
	
	<ul id="map_stats" >
		<?php foreach($count_guerreros as $count )
		{ ?>
			<li><strong><?php echo $count->Spanish_Country?></strong><?php echo $count->Total ?></li>
		<?php } ?>
	</ul>

<!-- homepage.php -->

	<div id="over_vaina">
		<a id="close_vaina"></a>
		<div id="over_stuff">
			<div id="video_container">
				<iframe src="http://player.vimeo.com/video/31925129?title=0&amp;byline=0&amp;portrait=0&amp;color=0ec341&amp;autoplay=0" width="638" height="423" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
			</div><!-- end of #youtube_container -->
			<div id="copy_info">
				<h2>Buscamos Guerreros</h2>
    		<p><strong>Guerreros de Luz</strong> es una iniciativa global de la Fundación Ricky Martin que tiene como objetivo formar un
    		ejército de paz que denuncie y actué en contra de la Trata Humana.</p>
    		<p><strong>Por sólo una donación de $2 dólares nos permitirás continuar abogando por el bienestar de la niñez alrededor del mundo.</strong></p>
				<a id="lightbox_register_cta" href="<?php echo site_url ('cuenta/registro') ?>">Hazte Guerrero con donación de $2</a>
			</div><!-- end of #copy_info -->
		</div><!-- end of #over_stuff -->
	</div><!-- end of #over_vaina -->

  <div id="homepage" class="wrapper clearfix">

    <div id="welcome_box">
			<a href="<?php echo site_url ('cuenta/registro'); ?>"><img id="cta_home_banner" src="<?php echo base_url('img/home/cta_home_banner.png') ?>" alt="" /></a>

    	<?php if (!empty ($email_success)): ?>
			<span class="success"><?php echo $email_success ?></span>
		<?php endif ?>
			<h2>¿Por qué me debo unir a Guerreros de Luz?</h2>
			<p>Fácil, estarás uniéndote a una <strong>iniciativa de recaudación de fondos</strong> donde serás partícipe de una comunidad virtual de guerreros en <strong>contra de la trata y la explotación infantil</strong>.</p>

			<ul class="clearfix">
				<li>
					<h3 id="comunidad">Comunidad de Guerreros</h3>
					<p>Sé parte de una comunidad virtual de gente comprometida con la lucha contra la trata humana y  la explotación infantil.</p>
					<strong>Haz nuevos amigos y aporta a la lucha.</strong>
				</li>
				<li>
					<h3 id="recompensas">Un Mundo de Recompensas</h3>
					<p>Participa de un juego interminable en contra de las fuerzas oscuras, reclutando guerreros y promoviendo la lucha con mensajes de luz.</p>
					<strong>Adquiere medallas al reclutar guerreros.</strong>
				</li>
				<li>
					<h3 id="combate">Combate la trata apoyando la Fundación</h3>
					<p>Ayuda a la <em>Fundación Ricky Martin</em> a seguir luchando en contra de la explotación infantil uniéndote a este esfuerzo.</p>
					<strong>Aporta a la causa mientras juegas.</strong>
				</li>
			</ul>

		</div>
	</div>
	
	<!-- Tracking / Heat map -->
	<script type="text/javascript">
		setTimeout(function(){var a=document.createElement("script");
		var b=document.getElementsByTagName('script')[0];
		a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0012/1146.js?"+Math.floor(new Date().getTime()/3600000);
		a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
	</script>
