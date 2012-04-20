<div class="wrapper">
  <a id="top_logo" href="<?php echo site_url ('social') ?>"><?php echo lang ('app_title') ?></a>
  <?php if ($this->uri->rsegment(1) != 'power') : ?>
	  <nav id="logged_menu">
	    <ul>
	      <li><a<?php if ($this->uri->rsegment (1) == 'social' && $this->uri->rsegment (2) == 'index') {?> class="selected"<?php }?> href="<?php echo site_url ('social') ?>"><?php echo lang ('app_dashboard_title') ?></a></li>
	      <li><a<?php if ($this->uri->rsegment (2) == 'users_search') {?> class="selected"<?php }?> href="<?php echo site_url ('social/users_search') ?>"><?php echo lang ('app_mundo_title') ?></a></li>
	      <li><a<?php if ($this->uri->rsegment (2) == 'light_map') {?> class="selected"<?php }?> href="<?php echo site_url ('home/light_map') ?>"><?php echo lang ('app_map_title') ?></a></li>
	    </ul>
	  </nav>
		<?php if (!empty ($this->guerrero)): ?>
		<div id="logged_info">
			<img src="<?php echo avatar_url ($this->guerrero->guerrero_avatar); ?>" class="user_pic" alt="" />
			<strong><?php echo $this->guerrero->guerrero_real_name ?></strong>
			<span><a href="<?php echo site_url ('social/perfil') ?>"><?php echo lang ('app_profile_title') ?></a></span>
			<span><a href="<?php echo site_url ('cuenta/logout') ?>"><?php echo lang ('app_profile_logout') ?></a></span>
		</div> <!-- end of logged_info -->
		<?php endif ?>
	<?php endif; ?>
</div>
