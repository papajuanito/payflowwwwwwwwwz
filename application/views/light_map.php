<!-- light_map.php -->
	<div id="flash_light_container" class="light_map">
		<div id="flash_light">
			<img src="<?php echo base_url('img/home/bg_stats_map.png') ?>">
		</div>
	</div>
	<ul id="map_stats" class="light_map_view">
		<?php foreach($count_guerreros as $count )
		{ ?>
			<li><strong><?php echo $count->Spanish_Country?></strong><?php echo $count->Total ?></li>
		<?php } ?>
		
	</ul>
