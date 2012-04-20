<!-- cuenta/inc.registration_nav.php -->
<nav id="steps">
  <ul id="<?php echo 'step' . $step ?>" class="clearfix">
  	<li<?php if ($step == 1) {?> class="selected"<?php }?>><a><?php echo lang ('cu_registro_step1_title') ?></a></li>
  	<li<?php if ($step == 2) {?> class="selected"<?php }?>><a><?php echo lang ('cu_registro_step2_title') ?></a></li>
  	<li<?php if ($step == 3 OR $step == 4) {?> class="selected"<?php }?>><a>Mi Donación</a></li>
  </ul>
</nav>
