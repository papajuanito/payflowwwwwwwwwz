<div id="statspage">
    <div class="statsblock">
    	<div class="stats_head">
    		<div class="title">
    			<h1>Dinero Recolectado</h1>
    		</div>
		</div>
		<div class="stats_content money">
			<div id="chart_col">
				<div id="money_chart"></div>
			</div>
			<div id="money_col">
			<div id="total_money_counter">
			<h1>Ingresos totales</h1>
				<div id="total_raised">
					$US <?php echo $raised_money ?>
					
				</div>
				
			</div>
			<div id="money_aprox">
				<ul>
					<li>
						Ingresos Estimados
					</li>
					<li>
						$US <?php echo number_format($raised_by_month,2) ?> - mensuales
					</li>
					<li>
						$US <?php echo number_format($raised_by_week,2) ?> - semanales
					</li>
					<li>
						$US <?php echo number_format($raised_by_day,2) ?> - diarios
					</li>
				</ul>
			</div>
			</div>
			<table class="users_table">
						<thead>
						<tr>
								<th>Transacción</th>
								<th>Nombre</th>
								<th>Método</th>
								<th>País</th>
								<th>Legión</th>
							</tr>
						</thead>
						<tbody>
						
						<?php foreach ($guerrero_list as $guerrero) {?>
						<tr>
							<td><?php echo $guerrero->guerrero_payment?></td>
							<td><?php echo $guerrero->guerrero_real_name?></td>
							<td>$US <?php echo $subscriptions[$guerrero->guerrero_subscription_type_id - 1]->subscription_type_fee; echo ($guerrero->guerrero_subscription_type_id == 1)? '': ' Mensuales' ; ?> </td>
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
