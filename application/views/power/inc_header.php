
<!--/inc_header.php -->

	<!-- !Navigation Widget -->
	<div id="user_widget">
		<h2><?php echo anchor ('power', lang ('app_title')) ?></h2>
		
		<div id="user_options">
		<ul>
			<li>Bienvenid@</li>
			<li><?php echo anchor ('power/log_out', 'Desconectarme') ?></li>
		</ul>
		</div>
		<div class="clear"></div>
	</div>
	<!-- end user_widget -->
	
	<!-- !Header -->
	<div id="header">

		<div id="logo_bar">
		<div class="clear"></div>
		</div>
		
		<div id="main_navigation">
			<ul>
				<li><?php echo anchor ('power', 'Estadísticas', $page == 'power'? array('class'=>'active'):NULL) ?></li>
				<li><?php echo anchor ('power/usuarios', 'Usuarios', $page=='usuarios'? array('class'=>'active'):NULL) ?></li>
			</ul>
		</div>
		
		
		<div id="sub_navigation">
			<ul>
				<?php if( $page == 'power'){ ?>
					<li><?php echo anchor ('power', 'Aplicación' , $section == 'app'? array('class' => 'active'): NULL)?></li>
					<li><?php echo anchor ('power/dinero', 'Dinero Recolectado', $section == 'dinero'? array('class' => 'active'): NULL)?></li>
				<?php }else if($page == 'usuarios' ){ ?>
					<li><?php echo anchor ('power/usuarios', 'Usuarios' , $section == 'usuarios'? array('class' => 'active'): NULL)?></li>
					<li><?php echo anchor ('power/top_users', 'Top User', $section == 'topuser'? array('class' => 'active'): NULL)?></li>
				<?php } ?>
			</ul>
			<div class="clear"></div>
		</div>
		
		
	</div>
	<div class="clear"></div>
	<!-- end header -->
