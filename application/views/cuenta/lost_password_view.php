<?php echo form_open_multipart ('cuenta/recover_password_proc', array ('id'=>'lost_password')) ?>
	<?php if (!$this->session->flashdata ('cu_recovery_success')): ?>
		<h3>Cambio de Contraseña</h3>
		<label for="recovery_email">Si la dirección de correo electrónico provista está asociada a una cuenta de Guerreros de Luz se le enviará un correo con instrucciones para recuperar la contraseña.</label>
		<input type="email" id="recovery_email" name="recovery_email"
			value="<?php echo $proc_response->email ?>"
			placeholder="Correo electrónico" required/>
		
		<?php if (!empty ($proc_response->error)): ?>
			<span class="error"><?php echo $proc_response->error ?></span>
		<?php endif ?>
		
		<input type="image" src="<?php echo base_url('img/btn_login.png') ?>"/>
	<?php else: ?>
		<p><?php echo $this->session->flashdata ('cu_recovery_success') ?></p>
		<a href="<?php echo site_url ('home') ?>">OK</a>
	<?php endif ?>
<?php echo form_close() ?>
