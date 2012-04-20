<?php $this->load->view('social/includes/inc.dashmenu.php') ?>

<div id="my_warriors" class="feed">
	<h2><?php echo lang ('so_guerreros_title') ?></h2>

	<ul>
		<?php foreach ($friend_list as $friend): ?>
		<li>
			<span class="badge_circle <?php echo $friend->legion_style; ?> <?php echo $friend->rank_style; ?>"></span>
			<img class="user_pic" src="<?php echo avatar_url ($friend->guerrero_avatar); ?>" alt="" width="60" height="60" />
			<h3><a href="<?php echo site_url ('social/perfil/'.$friend->guerrero_id) ?>"><?php echo $friend->guerrero_name ?></a></h3>
			<dl>
				<dt>Legión:</dt>
				<dd><?php echo lang ('app_'. $friend->legion_tag .'_name') ?></dd>
			</dl>
		</li>
		<?php endforeach ?>
	</ul>

</div>

<?php $this->load->view('social/includes/inc.sidebar.php') ?>