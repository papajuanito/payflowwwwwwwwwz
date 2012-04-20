<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="misrangos_section" class="feed">
	<h2>Mis Rangos</h2>
	
	<!-- Next rank to achieve -->
	<?php if ($next_rank_data) : ?>
		<div id="future_rank_top">&nbsp;</div>
		<div id="future_rank">
			<div id="future_rank_details">
				<img src="<?php echo base_url('/img/cuartel/ranks/' . $legion_data->legion_style . '/' . $next_rank_data->rank_style . '.png') ?>">
				<span><?php echo $next_rank_data->rank_name; ?></span>
				<p><?php echo lang('app_rank_desc_' . $next_rank_data->rank_style) ?></p>
			</div>
			<p>Ya lograste el rango de <strong><?php echo $rank_data->rank_name; ?></strong>. Sólo te faltan <strong><?php echo ($friends_left) ?> amigos</strong> para subir al rango de <strong><?php echo $next_rank_data->rank_name; ?></strong>. Continua reclutando guerreros y luchando contra la trata.</p>
			<div id="rank_progress">
				<div id="progress_bar" data-percent="<?php echo $percent; ?>"></div>
			</div>
		</div><!-- #future_rank -->
		<div id="future_rank_bottom">&nbsp;</div>
	<?php endif; ?>
	
	<!-- Current rank of the guerrero -->
	<div id="current_rank">
		<img src="<?php echo base_url('/img/cuartel/ranks/' . $legion_data->legion_style . '/' . $rank_data->rank_style . '_big.png') ?>">
		<div id="current_rank_details">
			<span><?php echo $rank_data->rank_name; ?></span>
			<p><?php echo lang('app_rank_desc_' . $rank_data->rank_style) ?></p>
		</div>
	</div>
	
	<!-- Completed ranks by the guerrero -->
	<?php if (!empty($completed_ranks)) : ?>
		<?php foreach($completed_ranks as $completed_rank) : ?>
			<div class="single box clearfix">
				<!-- Display RM badge if it is Ricky Martin -->
				<?php if ($this->guerrero->guerrero_id == 1) : ?>
					<img src="<?php echo base_url('/img/cuartel/ranks/rm/lightwarrior.png') ?>" alt="thumbnail">
				<?php else : ?>
					<img src="<?php echo base_url('/img/cuartel/ranks/' . $legion_data->legion_style . '/' . $completed_rank->rank_style . '.png') ?>" alt="thumbnail">
				<?php endif; ?>
				<div class="message box_content">
				  <span><?php echo $completed_rank->rank_name; ?></span>
				  <p><?php echo lang('app_rank_desc_' . $completed_rank->rank_style) ?></p>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
		<?php endforeach; ?>
		
		<!-- <a id="view_more_notifications" class="view_more" href="#">Ver M&aacute;s</a> -->
	<?php endif; ?>
	
</section><!-- end of #misrangos_section -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>