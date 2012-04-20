		<?php echo form_open_multipart ('social/send_message', array ('id'=>'post_box')); ?>
			<h2>Compartir Mensaje de Luz</h2>
			<span>Escribe tu mensaje de luz:</span>
			<textarea id="open_message" name="open_message" maxlength="250"></textarea>
			<p>&oacute; publica un mensaje de luz prescrito:</p>
			<select id="predefined_message" name="predefined_message">
				<option value="">-- selecciona un mensaje de luz --</option>
				<option>Juntos podemos hacer la diferencia. Protegerlos nos toca a todos.</option>
				<option>¡Haz la diferencia! Creemos un ejército en contra de la trata humana.</option>
				<option>¿Qué esperas para detener la trata humana? Únete al movimiento, recluta guerreros de luz.</option>
				<option>Se buscan guerreros de luz. Unidos podemos acabar con la trata humana.</option>
				<option>Detén la trata humana, es deber de todos velar por el bienestar de nuestros niños.</option>
				<option>Únete a este movimiento en contra de la trata humana. ¡Hagamos un ejército de luz!</option>
			</select>
			<!-- <p id="share_on">
				Compartir en:
				<span><input type="checkbox" value="Facebook" name="Facebook"> Facebook </span>
				<span><input type="checkbox" value="Twitter" name="Twitter"> Twitter</span>
			</p> -->
			
			<span id="maxCharacters">250</span><input id="cuartel_post_message" type="image" src="<?php echo base_url('/img/cuartel/btn_publish.png') ?>" alt="Publicar" />
			
			<?php if (isset ($msg_recipient) && is_numeric ($msg_recipient)): ?>
				<input type="hidden" name="recipient" value="<?php echo $msg_recipient; ?>"/>
			<?php endif; ?>
			<input type="hidden" name="ref" value="<?php echo $this->uri->rsegment (2) .'/'. $this->uri->rsegment (3); ?>"/>
		<?php echo form_close(); ?><!-- end of #post_box -->
