<!-- cuenta/registro_step1_view.php -->

<?php $this->load->view ('cuenta/includes/inc.registration_nav.php') ?>

<?php echo form_open_multipart ('cuenta/registro_proc', array('id' => 'register')) ?>
	<h2><?php echo lang ('cu_registro_step1_title') ?></h2>
	<p>¡Bienvenido a Guerreros de Luz! Estás a segundos de unirte. Un guerrero de luz lo define un llamado maestro. Escribe tu nombre de guerrero, escoge a qué legión de luz te vas a unir y comienza a batallar la trata humana y la explotación infantil.</p>

	<input type="hidden" id="step" name="step" value="<?php echo $step ?>"/>
	
	<fieldset id="choose_name">
		<label for="guerrero_name">Nombre de Guerrero</label>
		<input type="text" id="guerrero_name" name="guerrero_name" value="<?php echo $registration->guerrero_name ?>"<?php if (isset ($validation->guerrero_name)) {?> class="form_error"<?php }?>/>
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
		<?php if (isset ($validation->guerrero_name)): ?>
			<small class="error"><?php echo $validation->guerrero_name ?></small>
		<?php endif ?>
	</fieldset>
	
	<fieldset id="choose_legion">
		<h3>Legión de Luz</h3>
		<p>Selecciona tu legión de luz en el ejército de Guerreros de Luz. Cada legión tiene un llamado maestro que la define. Haz clic sobre la legión de tu preferencia.</p>
		
		<?php if (isset ($validation->guerrero_legion_id)): ?>
			<small class="error other"><?php echo $validation->guerrero_legion_id ?></small>
		<?php endif ?>
		
		<ul id="legion_list">
		<?php foreach ($legion_list as $legion): ?>
			<li<?php if ($legion->legion_id == $selected_legion) {?> class="selected"<?php }?> data-legion="<?php echo $legion->legion_tag ?>">
				<label for="legion_<?php echo $legion->legion_tag ?>"><?php echo lang ('app_'. $legion->legion_tag .'_name') ?></label>
				<input type="radio" id="legion_<?php echo $legion->legion_tag ?>" name="guerrero_legion_id" value="<?php echo $legion->legion_id ?>"<?php if ($legion->legion_id == $selected_legion) {?> checked<?php }?>/>
			</li>
		<?php endforeach ?>
		</ul>
	</fieldset>
	
	<ul id="legion_description_list">
	<?php foreach ($legion_list as $legion): ?>
		<li class="description_<?php echo $legion->legion_tag; if ($legion->legion_id == $selected_legion) {?> selected<?php }?>">
			<h3><strong><?php echo lang ('app_'. $legion->legion_tag .'_name') ?></strong></h3>
			<p><?php echo lang ('app_'. $legion->legion_tag .'_description') ?></p>
			<em><?php echo lang ('app_'. $legion->legion_tag .'_caption') ?></em>
		</li>
	<?php endforeach ?>
	</ul>
	
	<div class="clearfix">
		<input type="image" src="<?php echo base_url('img/cuenta/btn_next.png') ?>" alt="Proximo" id="submit_next" name="submit_next" value="<?php echo lang ('all_next') ?>"/>
	</div>
<?php echo form_close() ?>
