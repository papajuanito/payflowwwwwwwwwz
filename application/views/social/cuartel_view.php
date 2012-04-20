<?php if ($email_success) : ?>
	<div id="welcome_user">
		<h3>¡Felicidades!  Su aportación ayuda a la organización Fundación Ricky Martin a seguir luchando.</h3>
	</div>
<?php endif; ?>

<?php $this->load->view('social/includes/inc.dashmenu.php') ?>
<?php //print_r($notifications_list);?>
<div id="profile_index" class="feed" data-guerrero_id="<?php echo $this->guerrero->guerrero_id; ?>">
	<div id="top_notifications">
		<ul>
			<?php foreach ($notifications_list as $notification): ?>
		<?php switch ($notification->ticker_type):
		case 2:  // Notification: Faction Registrations ?>
			<li>
					<span class="faction_shield <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->ticker_obj_string; ?></strong> ha reclutado <?php echo count ($notification->guerreros); ?> nuevos miembros en la guerra contra la trata.</p>
			  		
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
			</li>
			<?php $last_registration = end ($notification->guerreros); ?>
			<?php break;
		
		case 3:  // Notification: Recompensa Won ?>
				<li>
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
					<span class="badge_wheel reward <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> ha ganado la recompensa <strong><?php echo $notification->ticker_obj_string; ?></strong>.</p>
				  	
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
				
				</li>
			<?php break;
		
		case 4:  // Notification: Invitation ?>
				<li>
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
<!-- 					<a class="green_btn" href="javascript:;" data-guerrero_id="<?php echo $notification->guerrero_id; ?>">Aceptar</a> -->
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> te ha invitado a unirse a su ejército.</p>
					
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
			</li>
			<?php break;
		
		case 5:  // Notification: Rank Promotion ?>
			<li>
				<img src="<?php echo avatar_url ($notification->guerrero_avatar); ?>" class="user_pic" alt="thumbnail"/>
					<span class="badge_wheel honor <?php echo $notification->ticker_obj_style; ?>"></span>
				  	<p><strong><?php echo $notification->guerrero_name; ?></strong> ha logrado llegar al rango de <strong><?php echo $notification->ticker_obj_string; ?></strong>.</p>
				  	
					<span class="not_date"><?php echo long_date ($notification->ticker_date_stamp); ?></span>
			</li>
			<?php break;
		endswitch; ?>
	<?php endforeach; ?>

		
	
		
		<!--

		
			<li>
				<img src="<?php echo base_url('/img/cuartel/img_placeholder_small.jpg') ?>" class="user_pic" alt="User">
				<span class="badge_wheel honor recruit"></span>
				<p><strong>-NOMBRE DE AMIGO-</strong> ha logrado llegar al rango de <strong>-NOMBRE DEL RANGO-</strong>.</p>
				<span class="not_date">11 de Septiembre, 2011</span>
			</li>
			<li>
				<img src="<?php echo base_url('/img/cuartel/img_placeholder_small.jpg') ?>" class="user_pic" alt="User">
				<p><strong>-NOMBRE DE AMIGO-</strong> ha logrado llegar al rango de <strong>-NOMBRE DEL RANGO-</strong>.</p>
				<span class="not_date">11 de Septiembre, 2011</span>
			</li>
-->
		</ul>
	</div> <!-- top_notifications -->
	<a id="view_more_notifications_top" class="view_more" href="<?php echo site_url ('social/notifications') ?>">Ver M&aacute;s</a>
	
	<?php $this->load->view ('social/includes/inc.message_form.php'); ?>

	<?php foreach ($message_list as $message): ?>
		<div class="single box clearfix">
			<?php if ($message->send_id != $this->guerrero->guerrero_id OR $message->send_id == 1): ?>
				<span class="badge_circle <?php echo $message->send_legion; ?> <?php echo $message->send_rank; ?>"></span>
				<a href="<?php echo site_url('social/perfil/' . $message->message_gue_id) ?>"><img src="<?php echo avatar_url ($message->send_avatar); ?>" class="user_pic" alt="thumbnail"></a>
			<?php endif; ?>
				
			<div class="message box_content">
				<span>
					<strong><a href="<?php echo site_url('social/perfil/' . $message->message_gue_id) ?>"><?php echo $message->send_name; ?></a></strong>
					<?php if ($message->send_id != $this->guerrero->guerrero_id && $message->recv_id == $this->guerrero->guerrero_id): ?>
						te
					<?php endif; ?>
					escribe:
				</span>
				<p><?php echo $message->message_text; ?></p>
			</div>
			<div class="box_btm">&nbsp;</div>
		</div>
	<?php endforeach; ?>
	
	<?php if (count ($message_list) >= 5): ?>
		<a id="view_more_notifications" class="view_more" href="javascript:;" data-last_checked_date="<?php echo $last_message->message_date; ?>">Ver M&aacute;s</a>
	<?php endif; ?>

</div><!-- end of #feed -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>
