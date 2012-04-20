<?php echo form_open_multipart ('cuenta/reset_password_proc/'.$recovery_token, array ('id'=>'password_reset', 'class'=>!(isset ($proc_response->general_error) OR isset ($proc_response->email_error) OR isset ($proc_response->password_error)) ? '' : 'error_form')) ?>
	<?php if (isset ($proc_response->general_error)): ?>
		<span class="error"><?php echo $proc_response->general_error ?></span>
	<?php endif ?>
	
	<h3>Cambio de Contraseña</h3>
	
	<fieldset class="icon_input">
		<label class="email_label" for="reset_email">Escribe tu dirección de correo electrónico.</label>
		<input type="email" id="reset_email" name="reset_email"
			value="<?php echo $proc_response->email ?>"
			placeholder="Correo electrónico" required/>
		
		<?php if (isset ($proc_response->email_error)): ?>
			<span class="error"><?php echo $proc_response->email_error ?></span>
		<?php endif ?>
	</fieldset>
	
	<fieldset class="icon_input">
		<label for="new_password">Escribe tu contraseña nueva.</label>
		<input type="password" id="new_password" name="new_password"
			placeholder="Contraseña nueva" required/>
	</fieldset>
	
	<fieldset class="icon_input">
		<label for="confirmation_password">Confirma tu contraseña nueva.</label>
		<input type="password" id="confirmation_password" name="confirmation_password"
			placeholder="Confirmar contraseña" required/>
		
		<?php if (isset ($proc_response->password_error)): ?>
			<span class="error other"><?php echo $proc_response->password_error ?></span>
		<?php endif ?>
	</fieldset>
	
	<input type="image" src="<?php echo base_url ('img/cuenta/btn_change.png') ?>"/>
<?php echo form_close() ?>
