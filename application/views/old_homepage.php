	<div id="homepage" class="wrapper clearfix">
		<div id="home_feed">
		
		<?php if (!empty ($email_success)): ?>
			<span class="success"><?php echo $email_success ?></span>
		<?php endif ?>
		
    	<div class="notification">
        <div class="entry-top">&nbsp;</div>
    		<div class="entry">
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    		</div>
        <div class="entry-btm">&nbsp;</div>
    	</div>
    </div>
  
    <div id="home_sidebar">
    	<div id="wanted_box">
    		<h3>Se buscan guerreros</h3>
    		<p>Únete al ejercito de paz de los guerreros de luz y combate la explotación infantil. ¡La acción comienza ahora!</p>
			<a id="hazte_guerrero" href="<?php echo site_url ('cuenta/registro') ?>">Hazte guerrero</a>
    	</div>
    		
    	<div id="login"<?php if (!empty ($auth_error)) {?> class="login_error"<?php }?>>
    		<?php echo form_open_multipart ('cuenta/authenticate', array ('id'=>'sign_in_form')) ?>
    			<h2>¡Conéctate y comienza la lucha!</h2>
    			
    			<?php if ($this->session->flashdata ('auth_error')): ?>
    				<span class="error"><?php echo $this->session->flashdata ('auth_error') ?></span>
    			<?php endif ?>
    			
    			<fieldset>
    				<ul>
    					<li>
    						<label for="user_email">Correo electrónico</label>
    						<input type="email" id="user_email" name="username"/>
    					</li>
    					<li>
    						<label for="user_password">Constraseña</label>
    						<input type="password" id="user_password" name="password"/>
    					</li>
    				</ul>
    				<span id="get_pass">
    					<a href="<?php echo site_url('cuenta/recover_password') ?>">Recuperar contraseña</a>
    				</span>
    				
    				<input type="image" src="<?php echo base_url('img/home/btn_ingresar.png') ?>" id="ingresar_" name="ingresar" alt="Ingresar"/>
    				
    				<input type="hidden" name="login_source" value="home"/>
    			</fieldset>
    		<?php form_close() ?>
    	</div>
    </div>