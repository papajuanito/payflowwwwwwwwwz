
		<?php if($message_list){
			
			foreach($message_list as $message){ ?>
				<tr>
					<td><img src="<?php echo base_url ('uploads/avatars') . '/'. $message->send_avatar ?>"/></td>
					<td>
						<?php echo '<h2>'.$message->send_name .'</h2>' .'Escribe: '; ?>
						<?php echo $message->message_text; ?><br>
						<a href="<?php echo site_url('power/delete_message/' . $message->message_id ) ?>">Borrar este mensaje</a>
					<br /><br />
					</td>
											
				<?php };?>
				</tr>
			<tr>
				
	
				<?php } else { echo "<td><h2>Este usuario no tiene mensajes<h2></td>" ;} ?>
	
			</tr>
	
