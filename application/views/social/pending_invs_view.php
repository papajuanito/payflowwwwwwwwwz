<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<section id="pending_invs" class="feed">
	<h2>Invitaciones Pendientes</h2>
	
	<?php if (empty($request_list)) : ?>
		<div class="no_items_warning">
			<div id="warning"></div>
			<h3>No tienes ninguna invitación pendiente.</h3>
			<p>Comienza a reclutar guerreros y a enviar mensajes de luz para aumentar tu ejercito de luz.</p>
		</div>
	<?php endif; ?>

	<ul>
	<?php foreach ($request_list as $request): ?>
		<li class="box clearfix">
			<div class="box_content">
				<span class="badge_circle <?php echo $request->legion_style; ?> <?php echo $request->rank_style; ?>"></span>
				<img class="user_pic" src="<?php echo avatar_url ($request->guerrero_avatar); ?>" alt="" height="50" width="50" />
				<h3><?php echo $request->guerrero_name ?></h3>
				<dl>
					<dt>Legión:</dt>
					<dd><?php echo lang ('app_'. $request->legion_tag .'_name') ?></dd>
				</dl>
				<div class="actions" data-guerrero_id="<?php echo $request->guerrero_id ?>">
					<a class="profile_btn accept" href="javascript:;"><?php echo lang ('all_accept') ?></a>
					<a class="profile_btn ignore" href="javascript:;"><?php echo lang ('all_ignore') ?></a>
					<small class="ajax_loader"><?php echo lang ('all_ajax_thinking') ?></small>
				</div> <!-- end of .actions -->
			</div> <!-- end of .box_content -->
			<div class="box_btm">&nbsp;</div>
		</li> <!-- end of .box -->
	<?php endforeach ?>
	</ul>
</section> <!-- end of #pending_invs -->

<?php $this->load->view('social/includes/inc.sidebar.php') ?>