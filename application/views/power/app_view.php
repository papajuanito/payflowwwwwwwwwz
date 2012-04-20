	<!--
<ul id="map_stats">
		<li><strong>San Juan</strong> 5,321</li>
		<li><strong>Nueva York</strong> 3,002</li>
		<li><strong>Mexico</strong> 2,297</li>
		<li><strong>USA</strong> 2,103</li>
	</ul>
-->

<!-- power_view.php -->
  <div id="statspage">
    <div class="statsblock">
    	<div class="stats_head">
    		<div class="title">
    			<h1>Cantidad de Usuarios</h1>
    		</div>
    		<h1 class="count"><?php echo $num_of_guerreros ?> usuarios</h1>
    		
    	</div>

    	<div class="stats_content">

    		<div id="legions_stats">
    			
	    		<ul>
	    			<li>Usuarios por Legiones</li>
	    			<?php 
	    				$legions_names = array_keys($legions_breakdown); 
	    				$i= 0;
	    			?>
	    			<?php foreach($legions_breakdown as $l){ ?>
	    			<li><strong><?php echo lang('app_'. $legions_names[$i] .'_name') ;?>:</strong> <?php echo $l." usuarios"?></li>
	    			<?php $i++; }?>
	       		</ul>
	       	</div>
	       	<div id="countrys_stats">
    			
	    		<ul>
	    			<li>Usuarios por País</li>
	    			<?php foreach($top_countrys as $country){ ?>
	    			<li><strong><?php echo $country->Spanish_Country ;?>:</strong> <?php echo $country->Total." usuarios"?></li>
	    			<?php }?>
	       		</ul>
	       	</div>

    	</div>

	</div><!-- #users stats -->
	<div class="statsblock">
    	<div class="stats_head">
    		<div class="title">
    			<h1>Recompensas</h1>
    		</div>
    		<h1 class="count"><?php echo $num_of_trophies ?> recompensas logradas</h1>
    		
    	</div>

    	<div class="stats_content">
    		<div id="trophies_stats">
	    		<ul>
	    			<li>Recompensas Logradas</li>
	    			<?php 
	    				$trophies_names = array_keys($trophies);
	    				$i=0;
	    			?>
	    			<?php foreach($trophies as $trophy) { ?>
	    			<li><strong><?php echo lang('app_'.$trophies_names[$i].'_name') ;?> : </strong><?php echo $trophy ?></li>
	    			<?php $i++; } ?>
	    		</ul>
			</div>
		</div>
	</div> <!-- recompensas stats -->
	<div class="statsblock">
		<div class="stats_head last">
			<div class="title">
				<h1>Mensajes de Luz</h1>
			</div>
			<h1 class="count"><?php echo $msg_total; ?> mensajes</h1>
		</div>
	</div>
  </div><!-- #statspage -->
