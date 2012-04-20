<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<div id="user_search" class="feed">
	<h2><?php echo lang ('so_search_title') ?></h2>

	<p id="page_desc">Busca y recluta guerreros del mundo entero cuya misión es ayudar a la Fundación Ricky Martin en la lucha contra la trata humana.</p>
	
	<?php if (empty($guerrero_list)) : ?>
		<div class="no_items_warning">
			<div id="warning"></div>
			<h3>No encontramos guerreros.</h3>
			<p>Intenta realizando otra búsqueda. Continua reclutando guerreros y creemos un ejercito invencible de guerreros de luz.</p>
		</div>
	<?php endif; ?>

	<ul id="top_warriors">
	<?php foreach ($guerrero_list as $i => $guerrero): ?>
		<li class="box clearfix">
			<div class="box_content">
				<img class="user_pic" src="<?php echo avatar_url ($guerrero->guerrero_avatar); ?>" alt="" width="50" height="50" />
				
				<?php if ($guerrero->guerrero_is_name_private): ?>
					<h3><a href="<?php echo site_url ('social/perfil/'.$guerrero->guerrero_id) ?>"><?php echo $guerrero->guerrero_name; ?></a></h3>
				<?php else: ?>
					<h3><a href="<?php echo site_url ('social/perfil/'.$guerrero->guerrero_id) ?>"><?php echo $guerrero->guerrero_real_name; ?></a></h3>
					<dl>
						<dt>Guerrero de Luz:</dt>
						<dd><?php echo $guerrero->guerrero_name ?></dd>
					</dl>
				<?php endif; ?>
				<dl>
					<dt>Legión:</dt>
					<dd><?php echo lang ('app_'. $guerrero->legion_tag .'_name') ?></dd>
				</dl>
				
				<div class="guerrero_stats">
					<span>
						<strong><?php echo $trophies[$guerrero->guerrero_id] ?></strong>
						<?php if (abs ($trophies[$guerrero->guerrero_id]) == 1): ?>
							Recompensa
						<?php else: ?>
							Recompensas
						<?php endif ?>
					</span>
					<span>
						<strong><?php echo $guerrero->friend_total ?></strong>
						<?php if (abs ($guerrero->friend_total) == 1): ?>
							<?php echo lang ('app_guerrero_singular') ?>
						<?php else: ?>
							<?php echo lang ('app_guerrero_plural') ?>
						<?php endif ?>
					</span>
				</div>
				<span class="badge_image <?php echo $guerrero->legion_style ?>"></span>
			</div>
			<div class="box_btm">&nbsp;</div>
		</li>
	<?php endforeach ?>
	</ul>
	
	<?php echo $this->pagination->create_links() ?>
</div><!-- end of #user_search -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>