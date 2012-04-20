<?php if (isset ($new_ranks) && !empty ($new_ranks)): ?>
	<!-- inc.rank_lightbox.php -->
	
	<div class="guerreros_lightbox"></div>
	
	<?php foreach ($new_ranks as $i => $rank): ?>
		<div class="rank_box">
			<h3>Haz logrado un nuevo rango</h3>
			<span class="<?php echo $this->guerrero->legion_style; ?> <?php echo $rank->rank_style; ?>"></span>
			<h4><?php echo $rank->rank_name; ?></h4>
			<p><?php echo lang ('app_rank_desc_' . $rank->rank_style); ?></p>
			
			<a class="close_lightbox" data-i="<?php echo $i; ?>" href="javascript:;">Close</a>
			
			<?php if (($i + 1) < count ($new_ranks)): ?>
				<a class="next_lightbox_cta" data-i="<?php echo $i; ?>" href="javascript:;">Próximo</a>
			<?php else: ?>
				<a href="<?php echo site_url ('social/mis_rangos'); ?>" class="rank_lightbox_cta">Mis Rangos</a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>
