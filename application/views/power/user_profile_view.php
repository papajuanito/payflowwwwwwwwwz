<?php
		 if($this->session->flashdata('acc_cancel_flash')){
			echo '<div id="flash_notif_success">';			 	
		 	echo $this->session->flashdata('acc_cancel_flash');
		 	echo '</div>';
		 }
		 else if($this->session->flashdata('acc_error_flash'))
		 {
		 	echo '<div id="flash_notif">';			 	
		 	echo $this->session->flashdata('acc_error_flash');
		 	echo '</div>';
		 }
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	
	var num_messages = <?php echo $message_number ?>;
	var loaded_messages = 0;
	
		$("#more_button").click(function(){
			
			loaded_messages += 5;
			$.get("<?php echo site_url('power/get_messages_profile') ?>/" + <?php echo $guerrero->guerrero_id ?> + "/" + loaded_messages, function(data){					
				$("#messages_table").append(data); 				
			});
 
			if(loaded_messages >= num_messages - 5)
			{
				$("#more_button").hide();
				//alert('hide');
			};
		});
	});
</script>


<div id="profile_content">
	<div id="user_name">
		<h2>Perfil: <?php echo $guerrero->guerrero_real_name; ?></h2>
	</div>
	<div id="user_stats">
		<div id="user_pic">
			<img src="<?php if (!$guerrero->guerrero_avatar)  echo base_url ('img/user_profiles/user_placeholder.png'); else echo base_url ('uploads/avatars/' . $guerrero->guerrero_avatar) ?>"/>
			
			<div class="stats" id="rank_badge_admin">
		  		<span class="<?php echo $guerrero->legion_style; ?> <?php echo $guerrero->rank_style; ?>"></span>
		  		<h4<?php if ($guerrero->rank_id == 1 || $guerrero->rank_id == 8) : ?> class="the_top" <?php endif; ?>> <?php echo $guerrero->rank_name; ?></h4>
		  		
		  	</div>
		  	<br/>
			<div class="stats">
				<h2><?php echo $trophies->count_t ?></h2> 
				<h4>Recompensa<?php if ($trophies->count_t != 1) echo 's'; ?></h4>
			</div>
			<br/>
			<div class="stats">
				<h2><?php echo $guerrero->friend_total ?></h2> <h4>Guerreros</h4>
			</div>
			<br/>
			<div class="stats">
				<h2><?php echo $message_number; ?></h2> <h4>Mensajes</h4>
			</div>
			
		</div>
	
	</div>
	<div id="user_information">
		<table border="border">
			<tr>
				<td><h1> <?php echo $guerrero->guerrero_name; ?></h1> <br /> <p> <?php echo $guerrero->guerrero_real_name; ?></p> </td>
				<td>
					<div id="legion_shield">
						<span  class="<?php echo 'legion_' . $guerrero->legion_tag; ?>"></span>
					</div>	
				</td>
				
			</tr>
			<tr>
				<td>Mi Facción: <p><?php echo lang ('app_'. $legion->legion_tag .'_name') ?></p></td>
				<td>Valor Favorito: <p><?php echo 'N/A'; ?></p></td>
			</tr>
			<tr>
				<td>Pueblo: <p><?php echo $guerrero->guerrero_town; ?></p></td>
				<td>País: <p><?php echo $guerrero->guerrero_country; ?> </p></td>
			</tr>
		</table>
		
		<h2 id="additional_info">Otros Datos</h2>
			
		<table>
			
			<tr>
				<td>Dirección postal: <br /> <p><?php echo $guerrero->guerrero_address_line1; ?>  <?php echo $guerrero->guerrero_address_line2; ?></p></td>
				<td>Teléfono: <p><?php echo $guerrero->guerrero_phone; ?></p></td>
			</tr>
			<tr>
				<td>Zip Code: <p><?php echo $guerrero->guerrero_zip; ?></p></td>
				<td>Tipo de Donación: <p><?php switch($guerrero->guerrero_subscription_type_id){
													case 1:
														echo 'Basic';
														break;
													case 2:
														echo 'Bronze';
														break;
													case 3:
														echo 'Silver';
														break;
													case 4:
														echo 'Gold';
														break;
										} ?></p></td>
			</tr>
			<tr>
				<td>Fecha de Nacimiento:  <br /> <p><?php echo $guerrero->guerrero_birthday; ?></p></td>
				<td>Fecha de Registración:  <br /> <p><?php echo $guerrero->guerrero_created; ?></p></td>
			</tr>
			<tr>
				<td>Género: <p><?php echo $guerrero->guerrero_gender; ?></p></td>
				<td>Última Conexión: <p><?php echo $guerrero->guerrero_last_login; ?></p></td>
			</tr>
			<tr>
				<td>Perfil de Facebook: <p>N/A </p></td>
				<td>Perfil de Twitter: <p>N/A </p></td>
			</tr>
		</table>
	</div>
	
	<div id="user_messages">
		<h1>Mensajes</h1> <br /> <br />
		<table id="messages_table">
			
			<tbody>
				<?php if($message_list){
					
					foreach($message_list as $message){ ?>
						<tr>
							<td><img src="<?php if (!$message->send_avatar) echo base_url ('img/user_profiles/user_placeholder.png'); else echo base_url ('uploads/avatars') . '/'. $message->send_avatar ?>"/></td>
							<td>
								<?php echo '<h2>'.$message->send_name .'</h2>' .'Escribe: '; ?>
								<?php echo $message->message_text; ?><br>
								<a href="<?php echo site_url('power/delete_message/' . $this->uri->segment(3) . '/' . $message->message_id ) ?>">Borrar este mensaje</a>
							<br /><br />
							</td>
													
						<?php };?>
						</tr>
					<tr>
						<?php } else { echo "<td><h2>Este usuario no tiene mensajes<h2></td>" ;} ?>
					</tr>
			</tbody>
		</table>
		<div id="more_button" <?php if($message_number <= 5){ ?>style="display:none;"<?php }?>></div>
	</div>







</div>