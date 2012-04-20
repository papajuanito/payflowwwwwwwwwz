<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="recompensas_section">
	<div id="rewards_popup">
		<img src="<?php echo base_url('/img/cuartel/recompensas/reward_super_popup.png'); ?>">
		<div id="reward_message">
			<h4>-NOMBRE DE AMIGO-</h4>
			<p>Proin aliquam ligula eu enim pharetra eget venenatis erat lobortis. Aenean sed nunc eget lorem vulputate mattis.</p>
		</div>
	</div>
	
	<ul id="all_rewards">
		
		
		<?php
		foreach ($recompensas_default as $trophy){
		?>
		<li>
		<?php 
		if($trophy->has_trophy)
		{
				$image ='reward_'.$trophy->trophy_style.'.png';
				$alt = $trophy->trophy_style;
		} 
		else
		{
				$image ='reward_'.$trophy->trophy_style.'_incomplete.png';
				$alt = $trophy->trophy_style.'_incomplete';
		}
		?>
				<img src="<?php echo base_url('/img/cuartel/recompensas/'.$image); ?>" alt="<?php echo $alt ?>">
				<h3><?php echo $trophy->trophy_name?></h3>

		</li>
		
		
	<?php }?>
	</ul>
</section><!-- end of #recompensas -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>