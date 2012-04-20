<div id="left_col">
<?php date_default_timezone_set('America/Puerto_Rico') ?>

<h1>Listado de Usuarios Registrados</h1>
			<table class="users_table">
			<thead>
				<tr class="toptr">
					
					<td colspan="4">
					<div id="orderby">
					<form action="<?php echo $base_url ?>" method="post" name="order_form">
					Ordenar por: 
					<select name="order_by">
						<option value="default"></option>
						<option value="guerrero_real_name" <?php echo ($order_by=='guerrero_real_name')? 'selected' : ''?>>Nombre</option>
						<option value="guerrero_email" <?php echo ($order_by=='guerrero_email')? 'selected' : ''?>>E-mail</option>
						<option value="guerrero_created" <?php echo ($order_by=='guerrero_created')? 'selected' : ''?>>Fecha de Registro</option>
						<option value="guerrero_last_login" <?php echo ($order_by=='guerrero_last_login')? 'selected' : ''?>>Última Conección</option>

					</select>
					</form>
					</div>
					<div id="per_page">
					<form action="<?php echo $base_url ?>" method="post" name="page_form">
					Por página:
					<select name="per_page" >
						<option value="5" <?php echo ($per_page=='5')? 'selected' : ''?>>5</option>
						<option value="10" <?php echo ($per_page=='10')? 'selected' : ''?>>10</option>
						<option value="20" <?php echo ($per_page=='20')? 'selected' : ''?> >20</option>
						<option value="50" <?php echo ($per_page=='50')? 'selected' : ''?>>50</option>
						<option value="100" <?php echo ($per_page=='100')? 'selected' : ''?>>100</option>
<!-- 						<option value="all">Todos</option> -->
					</select>
					</form>
					</div>
					</td>
				</tr>
				<tr>
					
					<th>Nombre</th>
					<th>E-mail</th>
					<th>Fecha de Registro</th>
					<th>Última Conección</th>
				</tr>
			</thead>
			
			<tbody>
				<form method="post" action="<?php echo site_url('power/user_options') ?>">
				
				<?php foreach($guerrero_list as $guerrero){?>
				<tr>
					<td><?php echo form_checkbox('select[]', $guerrero->guerrero_id) ?>
					<a href="<?php echo site_url('power/perfil/'.$guerrero->guerrero_id) ?>"><?php echo $guerrero->guerrero_real_name ?></a>
					</td>
					<td>
					 <?php echo $guerrero->guerrero_email ?>
					</td>
					<td>
					<?php echo date('d - m - Y',$guerrero->guerrero_created_stamp); ?>
					</td>
					<td>
					<?php 	$last = $guerrero->guerrero_last_login_stamp;
					 		$curr =  time();
					 		
					 	  $curr_year	= date('Y', $curr);
					 	  $curr_month	= date('n', $curr);
					 	  $curr_day		= date('j', $curr);
					 	  $curr_hour	= date('G', $curr);
					 	  $curr_min 	= date('i', $curr);
					 	  
					 	  $last_year 	= date('Y', $last);
					 	  $last_month	= date('n', $last);
					 	  $last_day 	= date('j', $last);
					 	  $last_hour	= date('G', $last);
					 	  $last_min		= date('i', $last);
					 	  
					 	  //activity print
					 	  if( ($curr_year - $last_year) > 0)//year
					 	  {
					 	  	echo   (($curr_year - $last_year)>1)? ($curr_year - $last_year)." años" : ($curr_year - $last_year)." año";
					 	  }
					 	  else if( ($curr_month - $last_month) > 0 )//month
					 	  {
					 	  	echo (($curr_month - $last_month)>1)? ($curr_month - $last_month)." meses" : ($curr_month - $last_month)." mes";
					 	  }
					 	  else if(($curr_day - $last_day) > 0 )//day
					 	  {
					 	  	echo (($curr_day - $last_day)>1)? ($curr_day - $last_day)." días" : ($curr_day - $last_day)." día";
					 	  }
					 	  else if(($curr_hour - $last_hour) > 0)//hour
					 	  {
					 	  	echo (($curr_hour - $last_hour)>1)? ($curr_hour - $last_hour)." horas" : ($curr_hour - $last_hour)." hora";
					 	  }
					 	  else if(($curr_min - $last_min) > 0)//minutes
					 	  {	
					 	  	echo (($curr_min - $last_min)>1)? ($curr_min - $last_min)." minutos" : ($curr_min - $last_min)." minuto";
					 	  }
					 ?>
					</td>
				</tr>
			<?php } ?>

			</tbody>
			</table>
			<div id="table_footer">
				<div id="edit_tools">
					<input type="checkbox" value="all" name="check_all" class="check_all">
					Seleccionar todos
					
						<input type="submit"  value="Activar" name="activar" id="test">
						<input type="submit" value="Desactivar" name="desactivar">
						<input type="submit" value="Bloquear" name="bloquear"> 
						<input type="submit" value="Eliminar" name="eliminar">
					</form>

				</div>
				<?php echo $this->pagination->create_links() ?>
				<div id="pages_counter">
					<?php echo $current_pages['inicial']."-".$current_pages['final_']." de ".$paginas." páginas ";?>
				</div>
			</div>
			
			
			
</div>

<div id="right_col">
			<h2>Búsqueda Avanzada</h2>
				<div class="sidebar_block">
					<?php echo form_open (site_url('power/usuarios'), array('id'=>'adv_search_form')) ?>
						<div class="field">
							<?php echo form_label ("Nombre del Guerrero", 'guerrero_name') ?>
							<?php echo form_input (array ('name'=>'guerrero_name', 'id'=>'guerrero_name')) ?>
						</div>
						<div class="field">
							<?php echo form_label ("E-Mail", 'guerrero_email') ?>
							<?php echo form_input (array ('name'=>'guerrero_email', 'id'=>'guerrero_email')) ?>
						</div>
														
						
					<?php echo form_close() ?>
					<a id="adv_search_btn" class="view_more" href="javascript:;">Buscar</a>
				</div>
				
				<h2>Crear Cuenta</h2>
				<div class="sidebar_block">
					<?php echo form_open () ?>
						<div class="field">
							<?php echo form_label ("Nombre del Guerrero", 'guerrero_name') ?>
							<?php echo form_input (array ('name'=>'guerrero_name', 'id'=>'guerrero_name')) ?>
						</div>
						<div class="field">
							<?php echo form_label ("Correo Electrónico", 'n_guerrero_email') ?>
							<?php echo form_input (array ('name'=>'n_guerrero_email', 'id'=>'n_guerrero_email')) ?>
						</div>
						

						<div class="field">
							<?php echo form_label ("Contraseña", 'guerrero_pass') ?>
							<?php echo form_password (array ('name'=>'guerrero_pass', 'id'=>'guerrero_pass')) ?>
						</div>
														
						
					<?php echo form_close() ?>
					<?php echo anchor ('#', 'Crear cuenta', 'class="submit_post"') ?>
				</div>



</div>

