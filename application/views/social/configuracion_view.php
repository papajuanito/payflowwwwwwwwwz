<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="configuracion_section" class="feed">
	<h2>Configuracion</h2>
	<div id="configuration_nav">
		<a id="datos_btn" href="javascript:;">Datos de Guerrero</a>
		<a id="perfil_btn" href="javascript:;">Mi Perfil</a>
		<a id="location_btn" href="javascript:;">Ubicaci&oacute;n</a>
		<a id="avatar_btn" class="nav_selected" href="javascript:;">Avatar</a>
	</div>
	
	<div id="avatar_configuration">
		<?php echo form_open_multipart ($form_action, array ('id'=>'update_avatar')); ?>
			<div id="select_avatar">
				<h4>Avatar de Perfil</h4>
				<p>Si deseas cambiar tu avatar puedes hacer seleccionar la foto que deseas subir y hacer clic en "Actualizar". También puedes seleccionar uno de nuestros avatares y hacer clic en "Actualizar".</p>
				
				<input type="file" name="uploaded_avatar" size="20">
				
				<p>&oacute; selecciona uno de nuestra fotos de avatar:</p>
				
				<div id="sample_avatars">
				<?php foreach ($default_avatars as $img): ?>
					<img src="<?php echo base_url($img['relative_path'].$img['name']) ?>" data-avatar_id="<?php echo $i; ?>" alt="Avatar default <?php echo $i; ?>"/>
				<?php $i++; endforeach; ?>
				</div>
			</div>
			
			<div id="select_predefined_avatar">
				<h4>Avatar Actual</h4>
				<p>Este es tu avatar actual. Si deseas eliminarlo haz clic sobre "Eliminar avatar actual".</p>
				<?php if (empty ($this->guerrero->guerrero_avatar)): ?>
					<img src="<?php echo base_url ('img/avatar_sample_big.jpg') ?>" id="user_avatar" alt="Avatar default"/>
				<?php else: ?>
					<img src="<?php echo avatar_url ($this->guerrero->guerrero_avatar); ?>" id="user_avatar" alt="Avatar para usuario <?php echo $this->guerrero->guerrero_name; ?>"/>
					<a id="eliminate_avatar" href="javascript:;">Eliminar avatar actual</a>
				<?php endif; ?>
			</div>
			<div class="green_line"></div>
			<input type="hidden" name="configuration_step" value="avatar"/>
			<input type="hidden" id="pre_loaded_image" name="pre_loaded_image"/>
			<input type="hidden" id="avatar_reset" name="avatar_reset"/>
			<input class="submit_avatar" type="image" src="<?php echo base_url ('img/update_avatar_btn.png') ?>" name="confirm_avatar"/>
		<?php echo form_close(); ?>
	</div><!-- avatar_configuration -->
	
	<div id="location_configuration">
		<h4>Ubicaci&oacute;n de Guerrero</h4>
		<p>Busque tu ubicación actual en el mapa y haz clic donde estás ubicado para posicionarte ó introduce tu país o ciudad en el campo de búsqueda.</p>
		<?php echo form_open_multipart ($form_action, array ('id'=>'update_location')); ?>
			<label for="country_search" >Búsqueda por ciudad o páis</label><input id="country_search" name="country_search" type="text" value="<?php echo $this->guerrero->guerrero_map_town; ?>">
				<div id="map_canvas" name="map_canvas" data-guerrero_id="<?php echo $this->guerrero->guerrero_id; ?>" class="selection_map"></div>
			<label for="lat_value">Latitud</label><input id="lat_value" name="lat_value" type="text" value="<?php echo $this->guerrero->guerrero_geo_lat; ?>">
			<label for="long_value">Longitud</label><input id="long_value" name="long_value" type="text" value="<?php echo $this->guerrero->guerrero_geo_long; ?>">
			
			<div class="green_line"></div>
			<input type="hidden" name="configuration_step" value="location">
			<input class="submit_avatar" type="image" src="<?php echo base_url('/img/update_avatar_btn.png') ?>" name="confirm_avatar">
		<?php echo form_close(); ?>
	</div><!-- location_configuration -->
	
	<div id="perfil_configuration">
		<?php echo form_open_multipart ($form_action); ?>
			<ul id="configuration_data">
				<li>
					<label for="warrior_nick">Apodo de Guerrero</label>
					<input id="warrior_nick" name="warrior_nick" type="text" value="<?php echo $this->guerrero->guerrero_name ?>">
				</li>
				<li>
					<label for="warrior_name">Nombre</label>
					<input id="warrior_name" name="warrior_name" type="text" value="<?php echo $this->guerrero->guerrero_real_name ?>">
					<span>
						<input id="public_name" name="public_name" type="checkbox" <?php echo ($this->guerrero->guerrero_is_name_private == 1)? 'checked': '' ?> >No hacer publico mi nombre
					</span>
				</li>
				
				<!--
<li>
					<label for="legions">Legión</label>
					<?php echo form_dropdown('legions', $legions_dropdown, $this->guerrero->guerrero_legion_id);?>
				</li>
-->
				<li>
					<label for="warrior_address">Direccion Postal</label>
					<input id="warrior_address" name="warrior_address" type="text" value="<?php echo $this->guerrero->guerrero_address_line1 ?>">
				</li>
				<li>
					<label for="warrior_town">Pueblo</label>
					<input id="warrior_town" name="warrior_town" type="text" value="<?php echo $this->guerrero->guerrero_town ?>">
					<span>
						<input id="public_town" name="public_town" type="checkbox" <?php echo ($this->guerrero->guerrero_is_loc_private == 1)? 'checked': '' ?>>No hacer publico mi pueblo
					</span>
				</li>
				<li>
					<label for="warrior_country">Pais</label>
					<?php $this->load->view ('cuenta/includes/inc.country_dropdown.php'); ?>
				</li>
				<li>
					<label for="warrior_zip">Zip-Code</label>
					<input id="warrior_zip"  name="warrior_zip" type="text" value="<?php echo $this->guerrero->guerrero_zip ?>">
				</li>
				<li>
					<label for="warrior_phone">Telefono</label>
					<input id="warrior_phone" name="warrior_phone" type="text" value="<?php echo $this->guerrero->guerrero_phone ?>">
				</li>
				<li> 
					<label for="guerrero_birthday_picker">Fecha de Nacimiento</label>
					<input id="guerrero_birthday_picker" type="text" value="<?php echo date("d / M / Y",$this->guerrero->guerrero_birthday_stamp); ?>">
					<input id="guerrero_birthday" name="guerrero_birthday"  type="text" value="<?php echo date("y-m-d",$this->guerrero->guerrero_birthday_stamp); ?>">
				</li>
				<!-- <li>
					<label for="social_networks">Medios Sociales</label>
					<a href="#"><img src="<?php echo base_url('/img/fb_connect_btn.png') ?>" alt="Facebook Connect"></a>
					<a href="#"><img src="<?php echo base_url('/img/twitter_connect_btn.png') ?>" alt="Twitter Connect"></a>
				</li> -->
			</ul>
			<div class="green_line"></div>
			<input type="hidden" name="configuration_step" value="profile">
			<input class="submit_avatar" type="image" src="<?php echo base_url('/img/update_avatar_btn.png') ?>" name="confirm_profile">
		<?php echo form_close(); ?>
	</div><!-- perfil_configuration -->
	
	
	
		<div id="datos_configuration">
		<?php echo form_open_multipart ($form_action); ?>
				<fieldset id="choose_name">
				<label for="guerrero_name">Nombre de Guerrero</label>
				<input type="text" id="guerrero_name" name="guerrero_name" value="<?php echo $this->guerrero->guerrero_name ?>">
				<strong>o</strong>
				<select id="guerrero_name_preset" name="guerrero_name_preset">
					<option value="">-- Seleciona un Nombre de Luz --</option>
					<option>Guerrero de Buena Voluntad</option>
					<option>Guerrero de Acción</option>
					<option>Guerrero Valiente</option>
					<option>Guerrero Libertad</option>
					<option>Guerrero de Fuego</option>
					<option>Guerrero Guía</option>
					<option>Guerrero de Energía</option>
					<option>Guerrero Honorable</option>
					<option>Guerrero Justiciero</option>
					<option>Guerrero de Paz</option>
					<option>Guerrero de Palabra</option>
				</select>
				
				</fieldset>
				
				<fieldset id="choose_legion">
				<h3>Legión de Luz</h3>
				<p>Selecciona tu legión de luz en el ejército de Guerreros de Luz. Cada legión tiene un llamado maestro que la define. Haz clic sobre la legión de tu preferencia.</p>
				
				<ul id="legion_list">
				<?php foreach ($legion_list as $legion): ?>
				<li<?php if ($legion->legion_id == $this->guerrero->guerrero_legion_id) {?> class="selected"<?php }?> data-legion="<?php echo $legion->legion_tag ?>">
				<label for="legion_<?php echo $legion->legion_tag ?>"><?php echo lang ('app_'. $legion->legion_tag .'_name') ?></label>
				<input type="radio" id="legion_<?php echo $legion->legion_tag ?>" name="guerrero_legion_id" value="<?php echo $legion->legion_id ?>"<?php if ($legion->legion_id == $this->guerrero->guerrero_legion_id) {?> checked<?php }?>/>
				</li>
				<?php endforeach ?>
				</ul>
				</fieldset>
			<input type="hidden" name="configuration_step" value="datos">
			<div class="green_line"></div>
			<input class="submit_avatar" type="image" src="<?php echo base_url('/img/update_avatar_btn.png') ?>" name="confirm_profile">
		<?php echo form_close(); ?>
	</div><!-- datos_configuration -->
	
	
	
	
	
</section><!-- end of #configuracion_section -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>

<?php if ($this->session->flashdata ('so_config_success') OR $this->session->flashdata ('so_config_error')): ?>
	<script type="text/javascript">
		<?php if ($this->session->flashdata ('so_config_success')): ?>
			alert ('<?php echo $this->session->flashdata ('so_config_success') ?>');
		<?php endif; ?>
		<?php if ($this->session->flashdata ('so_config_error')): ?>
			alert ('<?php echo $this->session->flashdata ('so_config_error') ?>');
		<?php endif; ?>
	</script>
<?php endif; ?>
