<!-- cuenta/registro_step2_view.php -->

<?php $this->load->view ('cuenta/includes/inc.registration_nav.php') ?>

<?php echo form_open_multipart ('cuenta/registro_proc', array('id' => 'register')) ?>
	<h2><?php echo lang ('cu_registro_step2_title') ?></h2>
	<p>Solo faltan 2 pasos para unirte. Escribe tu información personal y ubícate en el mapa de luz para comenzar la batalla contra la trata humana.</p>

	<input type="hidden" id="step" name="step" value="<?php echo $step ?>"/>
	
	<!-- !Left Column -->
	<fieldset id="profile_info">
		<fieldset>
			<ul>
				<li>
					<label for="guerrero_real_name_first">Nombre</label>
					<input type="text" id="guerrero_real_name_first" name="guerrero_real_name_first" value="<?php echo $registration->guerrero_real_name_first ?>"<?php if (isset ($validation->guerrero_real_name_first)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_real_name_first)): ?>
						<small class="error"><?php echo $validation->guerrero_real_name_first ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="guerrero_real_name_last">Apellido</label>
					<input type="text" id="guerrero_real_name_last" name="guerrero_real_name_last" value="<?php echo $registration->guerrero_real_name_last ?>"<?php if (isset ($validation->guerrero_real_name_last)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_real_name_last)): ?>
						<small class="error"><?php echo $validation->guerrero_real_name_last ?></small>
					<?php endif ?>
				</li>
			</ul>
			<small>
				<input type="checkbox" id="guerrero_is_name_private" name="guerrero_is_name_private"<?php if ($registration->guerrero_is_name_private) {?> checked<?php }?>/>
				<label for="guerrero_is_name_private">No hacer público mi nombre.</label>
			</small>
		</fieldset>
		<fieldset>
			<ul>
				<li>
					<label for="guerrero_email">Correo electrónico</label>
					<input type="email" id="guerrero_email" name="guerrero_email" value="<?php echo $registration->guerrero_email ?>" <?php if (isset ($validation->guerrero_email)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_email)): ?>
						<small class="error"><?php echo $validation->guerrero_email; ?></small>
					<?php endif ?>
					<small class="privacy_note">Tu correo electrónico no se hará público.</small>
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<ul>
				<li class="multi_input">
					<label for="guerrero_address_line1">Dirección Postal</label>
					<input type="text" class="first<?php if (isset ($validation->guerrero_address_line1)) {?> form_error<?php }?>" id="guerrero_address_line1" name="guerrero_address_line1" value="<?php echo $registration->guerrero_address_line1 ?>"/><br/>
					<input type="text" name="guerrero_address_line2" value="<?php echo $registration->guerrero_address_line2 ?>"<?php if (isset ($validation->guerrero_address_line1)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_address_line1)): ?>
						<small class="error"><?php echo $validation->guerrero_address_line1 ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="guerrero_town">Pueblo</label></h4>
					<input type="text" id="guerrero_town" name="guerrero_town" value="<?php echo $registration->guerrero_town ?>"<?php if (isset ($validation->guerrero_town)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_town)): ?>
						<small class="error"><?php echo $validation->guerrero_town ?></small>
					<?php endif ?>
				</li>
				<li> 
					<label for="guerrero_zip">Código Postal</label>
					<input type="text" id="guerrero_zip" name="guerrero_zip" value="<?php echo $registration->guerrero_zip ?>"<?php if (isset ($validation->guerrero_zip)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_zip)): ?>
						<small class="error"><?php echo $validation->guerrero_zip ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="country">País</label>
					<?php $this->load->view ('cuenta/includes/inc.country_dropdown.php') ?>
					<?php if (isset ($validation->guerrero_country)): ?>
						<small class="error"><?php echo $validation->guerrero_country ?></small>
					<?php endif ?>
					<small class="privacy_note">Tu dirección postal no se hará pública.</small>
				</li>
			</ul>
		</fieldset>
		<fieldset>
			<ul>
				<li>
					<label for="guerrero_phone">Teléfono</label>
					<input type="tel" id="guerrero_phone" name="guerrero_phone" value="<?php echo $registration->guerrero_phone ?>"<?php if (isset ($validation->guerrero_phone)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->guerrero_phone)): ?>
						<small class="error"><?php echo $validation->guerrero_phone ?></small>
					<?php endif ?>
					<small class="privacy_note">Tu teléfono no se hará público.</small>
				</li>
				<li>
					<label for="guerrero_birthday">Fecha de Nacimiento</label>
					<input type="text" id="guerrero_birthday_picker" name="guerrero_birthday_picker" value="<?php echo $registration->guerrero_birthday_picker ?>"<?php if (isset ($validation->guerrero_birthday)) {?> class="form_error"<?php }?>/>
					<input type="date" id="guerrero_birthday" name="guerrero_birthday" value="<?php echo $registration->guerrero_birthday ?>"/>
					<?php if (isset ($validation->guerrero_birthday)): ?>
						<small class="error"><?php echo $validation->guerrero_birthday ?></small>
					<?php endif ?>
				</li>
				<li>
					<label>Género</label>
					<div id="gender">
						<input type="radio" id="guerrero_gender_male"	name="guerrero_gender" value="M"<?php if ($registration->guerrero_gender == 'M') {?> checked<?php }?><?php if (isset ($validation->guerrero_gender)) {?> class="form_error"<?php }?>/>
						<label for="guerrero_gender_male">Masculino</label>
						<input type="radio" id="guerrero_gender_female"	name="guerrero_gender" value="F"<?php if ($registration->guerrero_gender == 'F') {?> checked<?php }?><?php if (isset ($validation->guerrero_gender)) {?> class="form_error"<?php }?>/>
						<label for="guerrero_gender_female">Femenino</label>
						<?php if (isset ($validation->guerrero_gender)): ?>
							<small class="error"><?php echo $validation->guerrero_gender ?></small>
						<?php endif ?>
					</div>
				</li>
			</ul>
		</fieldset>
	</fieldset>

	<!-- !Right Column -->
	<fieldset id="location">
		<h3>Ubicación de Guerrero</h3>
		<p>Busque tu ubicación actual en el mapa y haz clic donde estás ubicado para posicionarte ó introduce tu país o ciudad en el campo de búsqueda.</p>
		
		<ul>
			<li>
				<label for="guerrero_map_town">Búsqueda</label>
				<input type="text" id="guerrero_map_town" name="guerrero_map_town" value="<?php echo $registration->guerrero_map_town ?>"<?php if (isset ($validation->guerrero_map_town)) {?> class="form_error"<?php }?> />
			</li>
		</ul>
		
		<?php if (isset ($validation->guerrero_map_town)): ?>
			<span class="error other"><?php echo $validation->guerrero_map_town ?></span>
		<?php endif ?>
		
		<div id="map_canvas" class="selection_map"></div>
		
		<ul>
			<li>
				<label for="guerrero_geo_lat">Latitud</label>
				<input type="text" id="guerrero_geo_lat" name="guerrero_geo_lat" value="<?php echo $registration->guerrero_geo_lat ?>"<?php if (isset ($validation->guerrero_map_town)) {?> class="form_error"<?php }?> disabled/>
			</li>
			<li>
				<label for="guerrero_geo_long">Longitud</label>
				<input type="text" id="guerrero_geo_long"  name="guerrero_geo_long" value="<?php echo $registration->guerrero_geo_long ?>"<?php if (isset ($validation->guerrero_map_town)) {?> class="form_error"<?php }?> disabled/>
			</li>
		</ul>
		<small>
			<input type="checkbox" id="guerrero_is_loc_private" name="guerrero_is_loc_private"<?php if ($registration->guerrero_is_loc_private) {?> checked<?php }?>/>
			<label for="guerrero_is_loc_private">No hacer público mi ubicación.</label>
		</small>
	</fieldset>
	
	<div class="clearfix">
		<a id="submit_prev" href="javascript:;"><?php echo lang ('cu_registro_previous') ?></a>
		<input type="image" src="<?php echo base_url('img/cuenta/btn_next.png') ?>" id="submit_next" name="submit_next" alt="<?php echo lang ('all_next') ?>"/>
	</div>
<?php echo form_close() ?>
