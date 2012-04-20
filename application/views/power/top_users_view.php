<div id="statspage">
    <div class="statsblock">
    	<div class="stats_head">
    		<div class="title">
    			<h1>Mejores Usuarios del Mundo</h1>
    		</div>
		</div>
		<div class="stats_content">			
			<table class="users_table">
						<thead>
						<tr>
								<th>Nombre</th>
								<th>País</th>
								<th>Legión</th>
								<th>Recompensas</th>
								<th>Mensajes de Luz</th>
							</tr>
						</thead>
						<tbody>
						
						<?php foreach ($guerrero_list as $guerrero) {?>
						<tr>
							<td><a href="<?php echo site_url('power/perfil/'.$guerrero->guerrero_id) ?>"><?php echo $guerrero->guerrero_real_name?></a></td>
							<td><?php switch($guerrero->guerrero_map_country)
									{
										case 'Commonwealth of Puerto Rico': 
										echo  'Puerto Rico';
										break;
										case 'USA' :
										echo  'Estados Unidos';
										break;
										case 'United States': 
										echo 'Estados Unidos';
										break;
										case 'Brazil': 
										echo 'Brasil';
										break;
										case 'UK': 
										echo 'Reino Unido';
										break;
										case 'EEUU': 
										echo 'Estados Unidos';
										break;
										case 'Dominican Republic': 
										echo 'República Dominicana';
										break;
										case 'Japan': 
										echo 'Japón';
										break;
										case 'Italy': 
										echo 'Italia';
										break;
										default : 
										echo $guerrero->guerrero_map_country;
									}	
							?></td>
							<td><?php echo lang('app_'.$guerrero->legion_tag.'_name')?></td>
							<td><?php echo $trophies[$guerrero->guerrero_id] ?> Recompensas</td>
							<td><?php echo $messages[$guerrero->guerrero_id] ?> mensajes</td>
						</tr>
						<?php } ?>
						</tbody>
			</table>
			
			<div id="table_footer">
							
							<?php echo $this->pagination->create_links() ?>
							<div id="pages_counter">
								<?php echo $current_pages['inicial']."-".$current_pages['final_']." de ".$paginas." páginas ";?>
							</div>
			</div>
		</div>
	</div>
</div>