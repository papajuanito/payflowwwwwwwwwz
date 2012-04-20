<div class="wrapper">
	<a id="top_logo" href="<?php echo site_url () ?>"><?php echo lang ('app_title') ?></a>
	<?php if ($this->uri->rsegment(1) != 'power') : ?>
		<nav>
			<a href="<?php echo site_url ('cuenta/login')    ?>"><img src="<?php echo base_url('img/btn_login.png') ?>"    alt="<?php echo lang('app_login') ?>"/></a>
			<a href="<?php echo site_url ('cuenta/registro') ?>"><img src="<?php echo base_url('img/btn_register.png') ?>" alt="<?php echo lang('app_register') ?>"/></a>
		</nav>
	<?php endif; ?>
</div>
