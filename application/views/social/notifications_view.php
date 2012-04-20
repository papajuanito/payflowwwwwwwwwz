<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="cuartel_notifications" class="feed">
	<h2>Notificaciones</h2>
	
	<?php foreach ($notifications_list as $notification): ?>
		<?php switch ($notification->ticker_type):
		case 2:  // Notification: Faction Registrations ?>
			<div class="single box clearfix">
				<div class="message box_content">
					<span class="faction_shield <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->ticker_obj_string; ?></strong> ha reclutado <?php echo count ($notification->guerreros); ?> nuevos miembros en la guerra contra la trata.</p>
				  	
				  	<?php foreach ($notification->guerreros as $guerrero): ?>
				  		<a href="<?php echo site_url ('social/perfil/' . $guerrero->guerrero_id); ?>">
				  			<img src="<?php echo avatar_url ($guerrero->guerrero_avatar); ?>" class="fb_pic"
				  				alt="<?php echo $guerrero->guerrero_name; ?>"
				  				title="<?php echo $guerrero->guerrero_name; ?>"/>
				  		</a>
			  		<?php endforeach; ?>
			  		
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
			<?php $last_registration = end ($notification->guerreros); ?>
			<?php break;
		
		case 3:  // Notification: Recompensa Won ?>
			<div class="single box clearfix">
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
				<div class="message box_content">
					<span class="badge_wheel reward <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> ha ganado la recompensa <strong><?php echo $notification->ticker_obj_string; ?></strong>.</p>
				  	
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
			<?php break;
		
		case 4:  // Notification: Invitation ?>
			<div class="single box clearfix">
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
				<div class="message box_content">
					<a class="green_btn" href="javascript:;" data-guerrero_id="<?php echo $notification->guerrero_id; ?>">Aceptar</a>
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> te ha invitado a unirse a su ejército.</p>
					
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
			<?php break;
		
		case 5:  // Notification: Rank Promotion ?>
			<div class="single box clearfix">
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
				<div class="message box_content">
					<span class="badge_wheel honor <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> ha logrado llegar al rango de <strong><?php echo $notification->ticker_obj_string; ?></strong>.</p>
				  	
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
				</div>
				<div class="box_btm">&nbsp;</div>
			</div>
			<?php break;
		endswitch; ?>
	<?php endforeach; ?>
	
	<?php if (count ($notifications_list) >= 6): ?>
		<a id="view_more_notifications" class="view_more" href="javascript:;"
			data-last_checked_date="<?php echo $last_notification->ticker_date; ?>"
			data-last_registration_date="<?php echo $last_registration->ticker_date; ?>">Ver M&aacute;s</a>
	<?php endif; ?>
	
</section><!-- end of #cuartel_notifications -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>