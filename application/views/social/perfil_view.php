<div id="dash_menu">
  <div id="user_info">
  	<span class="legion_emblem <?php echo $guerrero->legion_style; ?>"></span>
  	<span class="sub_type <?php echo $subscription_type->subscription_type_tag; ?>"></span>
  	<a id="edit_profile" href="<?php echo site_url('social/configuracion') ?>">Editar Perfil</a>
    <a href="<?php echo site_url('social/perfil/') ?>"><img class="user_pic" src="<?php echo avatar_url ($guerrero->guerrero_avatar); ?>" alt="" /></a>
    <div id="sub_user_info">
		<strong><?php echo $guerrero->guerrero_name; ?></strong>
		<?php if (!$guerrero->guerrero_is_name_private): ?>
			<p class="real_name"><?php echo $guerrero->guerrero_real_name; ?></p>
		<?php endif; ?>
	    
    	<p><span>Legión:</span> <?php echo lang('app_' . $guerrero->legion_tag . '_name') ?></p>
    	<?php if ($guerrero->guerrero_id != $this->guerrero->guerrero_id) : ?>
    		<?php 
    		
    		switch ($friendship)
    		{
    			case 'friends':
    			break;
    			case 'waiting_on_them':
    				?>
    					Aún no ha aceptado tu invitación
    				<?php
    			break;
    			case 'waiting_on_you':
    				?>
    					Aún no has aceptado su invitación
    				<?php
    			break;
    			case 'they_ignored':
    				?>
    					<a class="profile_btn add" data-guerrero_id="<?php echo $guerrero->guerrero_id; ?>" href="javascript:;">Reclutar Guerrero</a>
    				<?php 
    			break;
    			case 'you_ignored':
	    			?>
    					<a class="profile_btn add" data-guerrero_id="<?php echo $guerrero->guerrero_id; ?>" href="javascript:;">Reclutar Guerrero</a>
    				<?php 
    			break;
    			case 'no_invites':
	    			?>
    					<a class="profile_btn add" data-guerrero_id="<?php echo $guerrero->guerrero_id; ?>" href="javascript:;">Reclutar Guerrero</a>
    				<?php 
    			break;

    		}
    		endif; 
    	?>
    </div>
  </div>
  
  	<div id="rank_badge">
  		<span class="<?php echo $guerrero->legion_style; ?> <?php echo $guerrero->rank_style; ?>"></span>
  		<h4<?php if ($guerrero->rank_id == 1 || $guerrero->rank_id == 8) : ?> class="the_top" <?php endif; ?>>
  			<?php echo $guerrero->rank_name; ?>
  		</h4>
  	</div>
  	
	<ul id="menu">
		<li>
			<strong><?php echo $recompensas->count_t ?></strong>
			Recompensa<?php if ($recompensas->count_t != 1) echo 's'; ?>
		</li>
		<li>
			<strong><?php echo $guerrero->friend_total ?></strong>
			<?php if (abs ($guerrero->friend_total) == 1): ?>
				<?php echo lang ('app_guerrero_singular') ?>
			<?php else: ?>
				<?php echo lang ('app_guerrero_plural') ?>
			<?php endif ?>
		</li>
		<li>
			<strong><?php echo $message_count; ?></strong>
			Mensaje<?php if ($message_count != 1) echo 's'; ?>
		</li>

	</ul>
</div>

<div id="map_canvas" data-guerrero_id="<?php echo $guerrero->guerrero_id?>"></div>
<div class="feed">
	<a id="edit_profile" href="<?php echo site_url('social/configuracion') ?>">Editar Perfil</a>
	<?php
	if ($guerrero->guerrero_id != $this->guerrero->guerrero_id)
	{
		if( $friendship == 'friends'){
		 	$this->load->view ('social/includes/inc.message_form.php'); 
		 }
	}
	else
	{
	 	$this->load->view ('social/includes/inc.message_form.php'); 
	}
	?>
	
	<?php if (empty ($message_list)): ?>
		<div id="some_info">
			<a id="add_info" href="javascript:;">Add Info</a>
			<p>Publica tu primer mensaje de luz y comienza a participar de un juego interminable en contra de las fuerzas oscuras, reclutando guerreros y promoviendo la lucha con mensajes de luz.</p>
		</div>
	<?php else: ?>	
		<?php foreach ($message_list as $message): ?>
			<div class="single box clearfix">
				<!--a id="delete_item" href="#">Borrar</a-->
				<?php if ($message->send_id != $guerrero->guerrero_id OR $message->send_id == 1): ?>
					<span class="badge_circle <?php echo $message->send_legion; ?> <?php echo $message->send_rank; ?>"></span>
					<a href="<?php echo site_url('social/perfil/' . $message->message_gue_id) ?>"><img src="<?php echo avatar_url ($message->send_avatar); ?>" class="user_pic" alt="thumbnail"></a>
				<?php endif; ?>
					
				<div class="message box_content">
					<span><strong><a href="<?php echo site_url('social/perfil/' . $message->message_gue_id) ?>"><?php echo $message->send_name; ?></a></strong> escribe:</span>
					<p><?php echo $message->message_text; ?></p>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
		<?php endforeach; ?>
		
		<?php if (count ($message_list) >= 5): ?>
			<a id="view_more_notifications" class="view_more" href="javascript:;" data-last_checked_date="<?php echo $last_message->message_date; ?>">Ver M&aacute;s</a>
		<?php endif; ?>
	<?php endif; ?>
</div> <!-- end of #feed -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>
