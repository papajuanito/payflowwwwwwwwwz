<!-- cuenta/registro_step4_view.php -->

<?php $this->load->view ('cuenta/includes/inc.registration_nav.php') ?>

<?php echo form_open_multipart ('cuenta/registro_proc', array('id' => 'register')) ?>
	<input type="hidden" id="step" name="step" value="<?php echo $step ?>"/>
	
	<h2><?php echo lang ('cu_registro_step3_title') ?></h2>
	<p>El pago fue autorizado. Ya casi eres un Guerrero de Luz. Haz clic en "Confirmar" para terminar la transacción.</p>
	
	<ul id="subscription_type_list" class="no_highlight">
		<li class="selected">
			<div>
				<img src="<?php echo base_url('img/cuenta/'. $subscription_type->subscription_type_tag  .'_star.png') ?>" alt="Ícono para subscripción '<?php echo $subscription_type->subscription_type_tag ?>'" />
			</div>
			<label for="subscription_<?php echo $subscription_type->subscription_type_tag ?>">
				<span>Donativo <?php if ($subscription_type->subscription_type_tag == 'basic'): ?>de<?php else: ?>recurrente<?php endif; ?></span>
				<strong>$<?php echo $subscription_type->subscription_type_fee ?> US</strong>
				<?php if ($subscription_type->subscription_type_tag == 'basic'): ?>una sóla vez<?php else: ?>al mes<?php endif; ?>
			</label>
		</li>
		<li id="confirm_copy">
			<h3>¿Cómo mi donación ayuda a la lucha contra la trata?</h3>
			<p>Gracias por apoyar nuestra causa. Con tu donativo fortaleces nuestras iniciativas educativas, investigaciones y proyectos comunitarios que protejan a la niñez de este escabroso crimen. Únete a nuestro ejercito de luz y luchemos juntos contra la trata humana.</p>
		</li>
	</ul>
	<br />
	<!--
	<fieldset id="confirmation">
		<input type="checkbox" id="cc_billing_approval" name="cc_billing_approval"/>
		<label for="cc_billing_approval">Al confirmar la transacción estarás aprobando tu donación mensual <strong>automática</strong> de $<?php echo $subscription_type->subscription_type_fee ?></label>
		<?php if (isset ($validation->cc_billing_approval)): ?>
			<small class="error other"><?php echo $validation->cc_billing_approval ?></small>
		<?php endif ?>
	</fieldset>
	-->
	
	<div class="clearfix">
		<a id="submit_prev" href="javascript:;"><?php echo lang ('cu_registro_previous') ?></a>
		<input type="image" src="<?php echo base_url('img/cuenta/btn_confirm.png') ?>" id="submit_next" name="submit_next" alt="<?php echo lang ('all_confirm') ?>"/>
	</div>
<?php echo form_close() ?>
