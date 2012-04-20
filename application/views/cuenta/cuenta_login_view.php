<div id="register_cta">
	<h3>¿Aún no eres guerrero?</h3>
	<p>Únete a esta iniciativa de recaudación de fondos donde serás partícipe de una comunidad virtual de guerreros en contra de la trata humana.</p>
	<a href="<?php echo site_url('cuenta/registro') ?>"><img src="<?php echo base_url('img/home/btn_register_home.png') ?>" alt="Hazte Guerrero" /></a>
</div>

<?php echo form_open_multipart ('cuenta/authenticate', array ('id'=>'login_form', !$this->session->flashdata ('auth_error') ? NULL : 'class'=>'error_form')) ?>
	<h3>Ingresar a mi cuenta</h3>
	<p>Inserta tu correo electrónico y contraseña en este espacio y haz clic en “Ingresar” para comenzar la guerra contra la trata humana.</p>
	
	<?php if ($this->session->flashdata ('auth_error')): ?>
		<span class="error"><?php echo $this->session->flashdata ('auth_error') ?></span>
	<?php endif ?>
	
	<label for="username">Correo Electrónico</label>
	<input type="email" id="username" name="username" required/>
	
	<label for="password">Contraseña</label>
	<input type="password" id="password" name="password" required/>
	
	<span id="get_pass">
		<a href="<?php echo site_url('cuenta/recover_password') ?>">Recuperar contraseña</a>
	</span>
	
	<input type="image" src="<?php echo base_url('/img/btn_login.png') ?>"/>
	
	<input type="hidden" name="login_source" value="login"/>
<?php echo form_close() ?>
