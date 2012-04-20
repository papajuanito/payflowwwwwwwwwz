<!-- cuenta/registro_step3_view.php -->

<?php $this->load->view ('cuenta/includes/inc.registration_nav.php') ?>

<?php echo form_open_multipart ('cuenta/registro_proc', array('id' => 'register')) ?>
	<h2><?php echo lang ('cu_registro_step3_title') ?></h2>
	<p>La inscripción de $2 USD te convierte en un Guerrero de Luz</p>
	
	<?php if (isset ($validation->guerrero_subscription_type_id)): ?>
		<span class="error other"><?php echo $validation->guerrero_subscription_type_id ?></span>
	<?php endif ?>
	
	<input type="hidden" id="step" name="step" value="<?php echo $step ?>"/>

	<ul id="subscription_type_list">
	<?php foreach ($subscription_type_list as $subscription_type): ?>
		<li<?php if ($subscription_type->subscription_type_id == $selected_subscription_type) {?> class="selected"<?php }?>>
			<div>
				<img src="<?php echo base_url('img/cuenta/'. $subscription_type->subscription_type_tag .'_star.png') ?>" alt="Ícono para subscripción '<?php echo $subscription_type->subscription_type_tag ?>'" />
				<input type="radio" id="subscription_<?php echo $subscription_type->subscription_type_tag ?>" name="guerrero_subscription_type_id" value="<?php echo $subscription_type->subscription_type_id ?>"<?php if ($subscription_type->subscription_type_id == $selected_subscription_type) {?> checked<?php }?>/>
			</div>
			<label for="subscription_<?php echo $subscription_type->subscription_type_tag ?>">
				<span>Donativo <?php if ($subscription_type->subscription_type_tag == 'basic'): ?>de<?php else: ?>recurrente<?php endif; ?></span>
				<strong>$<?php echo $subscription_type->subscription_type_fee ?> US</strong>
				<?php if ($subscription_type->subscription_type_tag == 'basic'): ?>una sóla vez<?php else: ?>al mes<?php endif; ?>
			</label>
		</li>
	<?php endforeach ?>
	</ul>


	<fieldset id="billing_info" class="billing">
		<h3>Información de Tarjeta de Crédito</h3>
		
		<?php if (isset ($validation->cc_general)): ?>
			<span class="error"><?php echo $validation->cc_general ?></span>
		<?php endif ?>
		<div class="bg_round">
			<ul class="card_rules">
				<li>Solo aceptamos Visa y Mastercard.</li>
				<li>No se aceptan AMEX y Discovery.</li>
			</ul>
			<ul class="card_details">
				<li>
					<label for="cc_number">Número de la tarjeta de crédito</label><small class="right_message">(sin guiones ni espacios)</small>
					<input type="text" id="cc_number" name="cc_number" value="<?php echo $registration->cc_number ?>"<?php if (isset ($validation->cc_number)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_number)): ?>
						<small class="error"><?php echo $validation->cc_number ?></small>
					<?php endif ?>
					<span class="accepted_cards"></span>
					<small class="card_info">Solo aceptamos Visa y Mastercard.</small>
				</li>
				<li>
					<label for="cc_expiration_month">Fecha de vencimiento</label>
					<select id="cc_expiration_month" name="cc_expiration_month"<?php if (isset ($validation->cc_expiration_date)) {?> class="form_error"<?php }?>>
						<option value="01"<?php if ($registration->cc_expiration_month == '01') {?> selected<?php }?>>01 - enero</option>
						<option value="02"<?php if ($registration->cc_expiration_month == '02') {?> selected<?php }?>>02 - febrero</option>
						<option value="03"<?php if ($registration->cc_expiration_month == '03') {?> selected<?php }?>>03 - marzo</option>
						<option value="04"<?php if ($registration->cc_expiration_month == '04') {?> selected<?php }?>>04 - abril</option>
						<option value="05"<?php if ($registration->cc_expiration_month == '05') {?> selected<?php }?>>05 - mayo</option>
						<option value="06"<?php if ($registration->cc_expiration_month == '06') {?> selected<?php }?>>06 - junio</option>
						<option value="07"<?php if ($registration->cc_expiration_month == '07') {?> selected<?php }?>>07 - julio</option>
						<option value="08"<?php if ($registration->cc_expiration_month == '08') {?> selected<?php }?>>08 - agosto</option>
						<option value="09"<?php if ($registration->cc_expiration_month == '09') {?> selected<?php }?>>09 - septiembre</option>
						<option value="10"<?php if ($registration->cc_expiration_month == '10') {?> selected<?php }?>>10 - octubre</option>
						<option value="11"<?php if ($registration->cc_expiration_month == '11') {?> selected<?php }?>>11 - noviembre</option>
						<option value="12"<?php if ($registration->cc_expiration_month == '12') {?> selected<?php }?>>12 - diciembre</option>
					</select>
					<select id="cc_expiration_year" name="cc_expiration_year"<?php if (isset ($validation->cc_expiration_date)) {?> class="form_error"<?php }?>>
					<?php for ($i = 0; $i < 10; $i++): ?>	
						<option value="<?php echo $short_year + $i ?>"<?php if ($registration->cc_expiration_year == $short_year + $i) {?> selected<?php }?>><?php echo $long_year + $i ?></option>
					<?php endfor ?>
					</select>
					<?php if (isset ($validation->cc_expiration_date)): ?>
						<small class="error"><?php echo $validation->cc_expiration_date ?></small>
					<?php endif ?>
				</li>
				<li>	
					<label for="cc_security">Código de seguridad</label>
					<input type="text" id="cc_security" name="cc_security" value="<?php echo $registration->cc_security ?>" maxlength="4"<?php if (isset ($validation->cc_security)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_security)): ?>
						<small class="error"><?php echo $validation->cc_security ?></small>
					<?php endif ?>
					<small>(3 dígitos detrás de tarjetas MasterCard y Visa)</small>
				</li>
			</ul>
		</div>
	</fieldset>
	<fieldset id="billing_adr" class="billing">
		<h3>Dirección de Facturación</h3>
		
		<div class="bg_round">
			<ul class="card_rules">
				<li>Introduzca su nombre y apellido como aparecen en la factura de su tarjeta de credito.</li>
				<li>Escriba su dirección en el siguiente orden: número de calle o apartado, nombre de edificio, urbanización o condominio.</li>
				<li>Verifique varias veces que el código postal esta correcto.</li>
				<li>Confirme que su tarjeta de crédito esta autorizada para hacer transacciones internacionales o que su banco le permite hacer transacciones en línea.</li>
			</ul>
			
			<ul>
				<li>
					<label for="cc_billing_name_first">Nombre</label>
					<input type="text" id="cc_billing_name_first" name="cc_billing_name_first" value="<?php echo $registration->cc_billing_name_first ?>"<?php if (isset ($validation->cc_billing_name_first)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_billing_name_first)): ?>
						<small class="error"><?php echo $validation->cc_billing_name_first ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="cc_billing_name_last">Apellido</label>
					<input type="text" id="cc_billing_name_last" name="cc_billing_name_last" value="<?php echo $registration->cc_billing_name_last ?>"<?php if (isset ($validation->cc_billing_name_last)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_billing_name_last)): ?>
						<small class="error"><?php echo $validation->cc_billing_name_last ?></small>
					<?php endif ?>
					<small>Como aparecen en su factura.</small>
				</li>
				<li>
					<label for="cc_billing_address1">Dirección</label>
					<input type="text" id="cc_billing_address1" name="cc_billing_address1" value="<?php echo $registration->cc_billing_address1 ?>"<?php if (isset ($validation->cc_billing_address)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_billing_address)): ?>
						<small class="error"><?php echo $validation->cc_billing_address ?></small>
					<?php endif ?>
					<small>Calle, apartado, nombre de la empresa, etc&hellip;</small>
				</li>
				<li>
					<label for="cc_billing_address2">&nbsp;</label>
					<input type="text" id="cc_billing_address2" name="cc_billing_address2" value="<?php echo $registration->cc_billing_address2 ?>"<?php if (isset ($validation->cc_billing_address)) {?> class="form_error"<?php }?>/>
					<small>Apartamento, oficina, edificio, piso, etc.&hellip;</small>
				</li>
				<li>
					<label for="cc_billing_city">Ciudad</label>
					<input type="text" id="cc_billing_city" name="cc_billing_city" value="<?php echo $registration->cc_billing_city ?>"<?php if (isset ($validation->cc_billing_city)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_billing_city)): ?>
						<small class="error"><?php echo $validation->cc_billing_city ?></small>
					<?php endif ?>
				</li>
				<li<?php if (!in_array ($selected_country, array ('', 'CA', 'US', 'UM'))) {?> style="display: none;"<?php }?>>
					<label for="state">Estado</label>
					<?php $this->load->view ('cuenta/includes/inc.state_dropdown.php') ?>
					<?php if (isset ($validation->cc_billing_state)): ?>
						<small class="error"><?php echo $validation->cc_billing_state ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="cc_billing_zip">Código postal</label>
					<input type="text" id="cc_billing_zip" name="cc_billing_zip" value="<?php echo $registration->cc_billing_zip ?>"<?php if (isset ($validation->cc_billing_zip)) {?> class="form_error"<?php }?>/>
					<?php if (isset ($validation->cc_billing_zip)): ?>
						<small class="error"><?php echo $validation->cc_billing_zip ?></small>
					<?php endif ?>
				</li>
				<li>
					<label for="country">País</label>
					<?php $this->load->view ('cuenta/includes/inc.country_dropdown.php') ?>
					<?php if (isset ($validation->cc_billing_country)): ?>
						<small class="error"><?php echo $validation->cc_billing_country ?></small>
					<?php endif ?>
				</li>
			</ul>
		</div>
	</fieldset>

	<div class="clearfix">
		<a id="submit_prev" href="javascript:;"><?php echo lang ('cu_registro_previous') ?></a>
		<input type="image" src="<?php echo base_url('img/cuenta/btn_next.png') ?>" id="submit_next" name="submit_next" alt="<?php echo lang ('all_next') ?>"/>
	</div>
<?php echo form_close() ?>
