<div id="general_footer">
	<?php if ($this->uri->rsegment(1) != 'power') : ?>
		<?php if (!empty ($this->guerrero)): ?>
			<div id="social_share">
				<!-- Twitter Share -->
				<div class="twitter-share">
					<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.guerrerosdeluz.org" data-text="Únete a Guerreros de Luz, una comunidad virtual de guerreros en contra de la trata." data-via="rm_foundation">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				</div>
				
				<!-- Facebook Like -->
				<div class="fb-like" data-href="https://www.facebook.com/RickyMartinFoundation" data-send="false" data-width="200" data-show-faces="false"></div>
				
			</div><!-- #social_share -->
		<?php else : ?>
			<nav>
				<a href="<?php echo site_url ('cuenta/login')    ?>"><img src="<?php echo base_url('img/btn_login.png') ?>"    alt="<?php echo lang('app_login') ?>"/></a>
				<a href="<?php echo site_url ('cuenta/registro') ?>"><img src="<?php echo base_url('img/btn_register.png') ?>" alt="<?php echo lang('app_register') ?>"/></a>
			</nav>
		<?php endif; ?>
	<?php endif; ?>
	
	<p id="copyright">&copy;2012 <a href="http://rickymartinfoundation.org/" target="_blank">Fundación Ricky Martin</a>. <strong>Todos los Derechos Reservados.</strong></p>
	<p class="support">Problemas técnicos o de pago escriba a: <a href="mailto:support@guerrerosdeluz.org">support@guerrerosdeluz.org</a></p>
	<a id="logo_footer" href="<?php echo site_url() ?>"><?php echo lang ('app_title') ?></a>
</div>
