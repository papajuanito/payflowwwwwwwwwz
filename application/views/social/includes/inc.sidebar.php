<div id="sidebar">
	<?php if (isset ($more_friends) && !empty ($more_friends)): ?>
		<section id="recruit">
			<div class="sidebox">
				<h3><a href="<?php echo site_url ('social/reclutamiento') ?>">Reclutar Guerreros</a></h3>
				<p>Recluta más guerreros y expande tu ejército de luz. Mientras más reclutes más trofeos ganas.</p>
				
				<ul class="clearfix">
				<?php foreach ($more_friends as $mfriend): ?>
					<li><a href="<?php echo site_url ('social/perfil/' . $mfriend->guerrero_id); ?>"><img src="<?php echo avatar_url ($mfriend->guerrero_avatar); ?>" title="<?php echo (!$mfriend->guerrero_is_name_private)? $mfriend->guerrero_real_name :$mfriend->guerrero_name  ?>" /></a></li>
				<?php endforeach; ?>
				</ul>
			</div>
		</section> <!-- end of #recruit -->
		<a href="<?php echo site_url ('social/reclutamiento') ?>" class="view_more">Reclutar Guerreros</a>
	<?php endif; ?>
	
	<?php if (isset ($mywarriors)): ?>
		<section id="recruit">
			<div class="sidebox">
				<h3><a href="<?php echo site_url ('social/reclutamiento') ?>">Mis Guerreros</a></h3>
				<ul class="clearfix">
				<?php $i = 0; ?>
					<?php foreach ($friend_list as $friend): ?>
						<?php if($i > 19) break;?>
						<li><a href="<?php echo site_url ('social/perfil/' . $friend->guerrero_id) ?>"><img src="<?php echo avatar_url ($friend->guerrero_avatar); ?>" title="<?php echo (!$friend->guerrero_is_name_private)? $friend->guerrero_real_name :$friend->guerrero_name  ?>"/></a></li>
						<?php $i++; ?>
					<?php endforeach ?>
				</ul>
			</div>
		</section> <!-- end of #recruit -->
		<a href="<?php echo site_url ('social/reclutamiento') ?>" class="view_more">Mis Guerreros</a>
	<?php endif; ?>

	<?php if (isset($adv_search)): ?>
		<section id="adv_search">
			<div class="sidebox">
				<h3>Buscador Avanzado</h3>
				<p>Selecciona la legión y páis en que deseas buscar y recluta más guerreros para la lucha.</p>
				<form id="adv_search_form" action="<?php echo site_url ('social/users_search') ?>">
					<label for="search_legion">Legión</label>
					<select id="search_legion">
						<option value="">Todas</option>
						<?php foreach ($legion_list as $legion): ?>
							<option value="<?php echo $legion->legion_id ?>"<?php if (isset ($search_legion) && $search_legion == $legion->legion_id){?> selected<?php }?>><?php echo lang ('app_'. $legion->legion_tag .'_name'); ?></option>
						<?php endforeach; ?>
					</select>
					
					<label for="search_rank">Rango</label>
					<select id="search_rank">
						<option value="">Todos</option>
						<?php foreach ($rank_list as $rank): ?>
							<option value="<?php echo $rank->rank_id ?>"<?php if (isset ($search_rank) && $search_rank == $rank->rank_id){?> selected<?php }?>><?php echo $rank->rank_name; ?></option>
						<?php endforeach; ?>
					</select>
					
					<label for="search_country">País</label>
					<select id="search_country">
						<option value="">Todos</option>
						<?php foreach ($country_list as $country): ?>
							<option<?php if (isset ($search_country) && $search_country == $country){?> selected<?php }?>><?php echo $country->Spanish_Country ?></option>
						<?php endforeach; ?>
					</select>
				</form>
			</div>
		</section> <!-- end of #adv_search -->
		<a id="adv_search_btn" class="view_more" href="javascript:;">Buscar</a>
	<?php endif ?>

	<?php if (isset($pending_invs) && (count($request_list) > 0)): ?>
		<section id="side_pending_invs">
			<div class="sidebox">
				<h3><a href="<?php echo site_url ('social/pending_invs') ?>">Invitaciones Pendientes</a></h3>
				<ul>
				<?php foreach ($request_list as $request): ?>
					<li>
						<img src="<?php echo avatar_url ($request->guerrero_avatar); ?>" alt="" width="80" height="80" />
						<h4><?php echo $request->guerrero_name ?></h4>
						<dl>
							<dt>Legión:</dt>
							<dd><?php echo lang ('app_'. $request->legion_tag .'_name') ?></dd>
						</dl>
						<div class="actions" data-guerrero_id="<?php echo $request->guerrero_id ?>">
							<a class="profile_btn accept" href="javascript:;"><?php echo lang ('all_accept') ?></a>
							<a class="profile_btn ignore" href="javascript:;"><?php echo lang ('all_ignore') ?></a>
							<small class="ajax_loader"><?php echo lang ('all_ajax_thinking') ?></small>
						</div>
					</li>
				<?php endforeach ?>
				</ul>
			</div>
		</section> <!-- end of #pending_invs -->
		<a href="<?php echo site_url ('social/pending_invs') ?>" class="view_more">Invitar Guerreros</a>
	<?php endif ?>
	
	<?php if (isset ($recommended_warriors) && !empty ($recommended_warriors)): ?>
		<section id="recommended_warriors">
			<div class="sidebox">
				<h3><a href="<?php echo site_url ('social/reclutamiento') ?>">Guerreros Recomendados</a></h3>
				<ul>
					<?php foreach ($recommended_warriors as $rec_war): ?>
						<li>
							<div class="left">
								<a href="<?php echo site_url ('social/perfil/' . $rec_war->guerrero_id); ?>"><img src="<?php echo avatar_url ($rec_war->guerrero_avatar); ?>" alt="" width="80" height="80" /></a>
								<a href="javascript:;" class="green_btn" data-guerrero_id="<?php echo $rec_war->guerrero_id; ?>">ÚNETE a mi ejercito</a>
							</div>
							<div class="right">
								<h4><a href="<?php echo site_url ('social/perfil/' . $rec_war->guerrero_id); ?>"><?php echo $rec_war->guerrero_name; ?></a></h4>
								<?php if (!$rec_war->guerrero_is_name_private): ?>
									<h5>(<?php echo $rec_war->guerrero_real_name; ?>)</h5>
								<?php endif; ?>
								
								<p><span>Legión:</span> <?php echo lang ('app_'. $rec_war->legion_tag .'_name'); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section> <!-- #recommended_warriors -->
		<a href="<?php echo site_url ('social/pending_invs') ?>" class="view_more">Invitar Guerreros</a>
	<?php endif; ?>
	
	<?php if (isset($recompensas)  && is_object($recompensas) && $recompensas->count_t != 0): ?>
		 <section id="my_rewards">
			<div class="sidebox">
				<h3>Mis Recompensas</h3>
				<ul>
					<?php foreach ($recompensas->trophy_list as $trophy): ?>
						<li>
							<span class="reward_badge  <?php echo $trophy->trophy_style ?>"></span>
<!-- 							<h4><?php echo $trophy->trophy_name ?></h4> -->
<!-- 							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p> -->
						</li>
					<?php endforeach; ?>
				</ul>						
				<div class="clear"></div>

			</div>
		</section> <!-- #my_rewards -->
		<a href="<?php echo site_url ('social/recompensas') ?>" class="view_more">Ver M&aacute;s</a>
	<?php endif; ?>
	
	<?php if (isset($recompensas) && is_object($recompensas) && $recompensas->count_t == 0): ?>
		<section id="my_rewards_empty">
			<div class="sidebox_empty">
				<h3>Mis Recompensas</h3>
				<p>Aun no haz acumulado recompensas. Comienza a reclutar guerreros.</p>
				<a id="add_reward" href="#">Add Reward</a>
			</div>
		</section> <!-- #my_rewards_empty -->
				<a href="<?php echo site_url ('social/recompensas') ?>" class="view_more">Mis Recompensas</a>

	<?php endif; ?>
</div> <!-- end of #sidebar -->
