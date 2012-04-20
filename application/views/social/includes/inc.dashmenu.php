<div id="dash_menu">
  <div id="user_info">
    <a href="<?php echo site_url('social/perfil/') ?>"><img class="user_pic dashmenu" src="<?php echo avatar_url ($this->guerrero->guerrero_avatar); ?>" alt="" /></a>
    <a id="edit_profile" href="<?php echo site_url('social/configuracion') ?>">Editar Perfil</a>
    <strong><?php echo $this->guerrero->guerrero_name ?></strong>
    <span><?php echo $this->guerrero->guerrero_real_name ?></span>
  </div>
  <nav id="menu">
    <ul>
      <li<?php if ($this->uri->rsegment (2) == 'index') {?> class="selected"<?php }?>>
        <a class="cuartel" href="<?php echo site_url ('social') ?>"><?php echo lang ('app_dashboard_title') ?></a>
		<ul>
          <li <?php if ($this->uri->rsegment (2) == 'notifications') {?> class="sub_selected"<?php }?>>
          	<a href="<?php echo site_url ('social/notifications') ?>"><?php echo lang ('so_notificaciones_title') ?></a>
          </li>
        </ul>
      </li>
      
      <li<?php if ($this->uri->rsegment (2) == 'perfil') {?> class="selected"<?php }?>>
        <a href="<?php echo site_url ('social/perfil') ?>" class="perfil"><?php echo lang ('app_profile_title') ?></a>
      </li>
      
      <li<?php if ($this->uri->rsegment (2) == 'my_warriors') {?> class="selected"<?php }?>>
        <a href="<?php echo site_url ('social/my_warriors') ?>" class="guerreros"><?php echo lang ('so_guerreros_title') ?></a>
        <ul>
          <li<?php if ($this->uri->rsegment (2) == 'reclutamiento') {?> class="sub_selected"<?php }?>>
          	<a href="<?php echo site_url ('social/reclutamiento') ?>"><?php echo lang ('so_reclutamiento_title') ?></a>
          </li>
          <li<?php if ($this->uri->rsegment (2) == 'pending_invs') {?> class="sub_selected"<?php }?>>
          	<a href="<?php echo site_url ('social/pending_invs') ?>"><?php echo lang ('so_invitaciones_title') ?></a>
          </li>
        </ul>
      </li>
      
      <!-- New mis rangos menu -->
   <!--
   <li <?php if ($this->uri->rsegment (2) == 'mis_rangos') {?> class="selected"<?php }?>>
      	<a href="<?php echo site_url ('social/mis_rangos') ?>" class="recompensas"><?php echo lang ('so_rangos_title') ?></a>
      </li>
-->
      
      <li<?php if ($this->uri->rsegment (2) == 'recompensas') {?> class="selected"<?php }?>>
        <a href="<?php echo site_url ('social/recompensas') ?>" class="recompensas"><?php echo lang ('so_recompensas_title') ?></a>
         <ul>
          <li<?php if ($this->uri->rsegment (2) == 'mis_rangos') {?> class="sub_selected"<?php }?>>
          	<a href="<?php echo site_url ('social/mis_rangos') ?>"><?php echo lang ('so_rangos_title') ?></a>
          </li>
        </ul> 
      </li>
      
      <li<?php if ($this->uri->rsegment (2) == 'users_search') {?> class="selected"<?php }?>>
        <a class="mundo" href="<?php echo site_url ('social/users_search') ?>"><?php echo lang ('app_mundo_title') ?></a>
      </li>
      
      <li>
        <a href="<?php echo site_url ('home/light_map') ?>" class="mapa"><?php echo lang ('app_map_title') ?></a>
      </li>
      
      <li<?php if ($this->uri->rsegment (2) == 'configuracion') {?> class="selected"<?php }?>>
        <a href="<?php echo site_url ('social/configuracion') ?>" class="config"><?php echo lang ('so_configuracion_title') ?></a>
      </li>
      
    </ul>
  </nav>
</div>
