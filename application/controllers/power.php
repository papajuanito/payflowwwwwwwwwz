<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Power extends CI_Controller
{
	// !Class Variables
	//------------------------------------------
	
	var $body_id = 'power';
	
	
	
	// !Public Methods
	//------------------------------------------
	
	/**
	 * Contructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		//$this->output->enable_profiler (TRUE);

		$this->load->library('session');
		$this->load->model ('Guerrero_model');
	}
	
	
	//----
	
	
	/**
	 * Index Page for this controller.
	 */
	public function index()

	{	
		$this->lang->load ('app');


		
		if (!$this->session->userdata ('logged_in'))
			$this->check_login();
		else {
		

			$data['page_title']       = 'Estadisticas';
			$data['main_content']     = 'power/app_view';
			$data['show_map']         = true;
			$data['num_of_guerreros'] = 0;
			$data['raised_money']     = 0;
			$data['num_of_trophies']  = 0;
			$data['header_data']  = array ('page'=>$this->uri->segment(1), 'section'=>'app');
			
			$breakdown = $this->Guerrero_model->guerreros_breakdown();
			
			$legions_breakdown = $this->Guerrero_model->guerreros_by_legions();
			//print_r($legions_breakdown);
			$top_countrys = 	$this->Guerrero_model->get_most_popular_places(4);
			//print_r($top_countries);
			$trophies = $this->Guerrero_model->guerreros_by_trophies();
			$messages_total = $this->Guerrero_model->count_all_messages();
			foreach ($trophies as $t){
				$data['num_of_trophies'] += $t;
			}
			
			//print_r($trophies);
			foreach ($breakdown as $subscription_type) {
				$data['num_of_guerreros'] += $subscription_type->subscription_type_total;
				$data['raised_money']     += $subscription_type->subscription_type_total * $subscription_type->subscription_type_fee;
			}
			
			$guerreros_count_by_place = $this->Guerrero_model->get_most_popular_places();
			
			$data['count_guerreros'] 	= $guerreros_count_by_place;
			$data['legions_breakdown']  = $legions_breakdown;
			$data['top_countrys'] 		= $top_countrys;
			$data['trophies']	 		= $trophies;
			$data['msg_total']			= $messages_total;
			$this->load->view('power/power_template', $data);
		}
	}
	//----
	
	public function log_out(){
		$this->session->sess_destroy();

		
		redirect('power');
		
	}
	
	
	
	/**
	 * Admin Login
	 */
	public function check_login()
	{
		if (!$this->session->userdata ('logged_in'))
		{
			$username =$this->input->post('username');
		
			if (empty($username))
				redirect('power/login');
				
					
			$username = $this->input->post ('username');
			$password = $this->input->post ('password');
			
			
			
			
			if($username == 'guerreros'  && $password == '00guerreros00'){
					$this->session->set_userdata ('logged_in',	TRUE);
					$this->session->set_userdata ('username', 'user');
				}
		}
		redirect('power');
	}
			
	
	public function login(){
		$this->load->view('power/login');
				

	}
	
	//----
	/**
	 * Profile Controller.
	 */

	public function perfil ()
	{
		
		if (!$this->session->userdata ('logged_in'))
			$this->check_login();
			
		$this->load->helper ('form');
		$this->load->library('pagination');
		
        

		$view_data['main_content']      = 'power/user_profile_view';
		$view_data['page_title']        = 'Perfil de Usuario';//lang ('app_profile_title');

		$view_data['header_data']  = array ('page'=>'usuarios', 'section'=>'usuarios');
		$guerrero_id = $this->uri->segment(3);
		
    	
    	try{
			if (empty ($guerrero_id)
				OR !is_numeric ($guerrero_id)
				OR !$guerrero = $this->Guerrero_model->guerreros ($guerrero_id))
					throw new Exception;
			
			$view_data['guerrero']      = $guerrero;
			
		}
    	
    	catch (Exception $e){
    		// Defaults to current user's profile
    		//$view_data['guerrero'] = $this->guerrero;
    	}
    	$rank_id = $view_data['guerrero']->guerrero_rank_id;
    	$legion_id = $view_data['guerrero']->guerrero_legion_id;
    	/*Pagination
		$config = array();
        $config["base_url"] = base_url().'index.php/power/perfil/'.$view_data['guerrero']->guerrero_id ;
        $config["total_rows"] = $this->Guerrero_model->message_count_by_guerrero($view_data['guerrero']->guerrero_id);
        $config["per_page"] = 5;
        $config["uri_segment"] = 4;
		$config['next_link'] = "<div id='vermas'></div>";
		//$config['next_tag_close'] = '';
		$config['last_link'] = FALSE;
		$config['first_link'] = FALSE;
		$config['display_pages'] = FALSE;
		$config['prev_link'] = FALSE;

		$this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
    	
    	
    	
        $view_data["links"] = $this->pagination->create_links();*/

    	
    	$view_data['message_list']  = $this->Guerrero_model->messages_profile ($view_data['guerrero']->guerrero_id, 'timeline');
		//$this->get_messages_profile($view_data['guerrero']->guerrero_id);    	

    	$view_data['legion'] = $this->Guerrero_model->guerrero_legion($legion_id);
		$view_data['message_number'] = $this->Guerrero_model->message_count_by_guerrero($view_data['guerrero']->guerrero_id, 'timeline');
		$view_data['trophies'] 	= $this->Guerrero_model->trophies_by_guerrero($view_data['guerrero']->guerrero_id);
		$view_data['subscription_type'] = $this->Guerrero_model->subscription_type_by_id ($view_data['guerrero']->guerrero_subscription_type_id);
		$view_data['rank_name'] = $this->Guerrero_model->rank_by_id($rank_id);
    	
    	    	//catch (Exception $e) {}
    	
    	    	
    	
    	$this->load->view('power/power_template', $view_data);
    	

	}
	public function get_messages_profile(){
		
		echo $this->uri->segment(4);
		
		$data['message_list'] = $this->Guerrero_model->messages_profile($this->uri->segment(3), $this->uri->segment(4), 'timeline');
		
		echo $this->load->view('power/get_messages', $data);
	}
	/*public function delete_message(){
        $this->load->model('guerrero_model');
 
 		$message_id = $this->uri->segment(4);
        $user_id = $this->uri->segment(3);
 
        try{
            if(!is_numeric($message_id)){
                throw new Exception('El mensaje a borrar es invalid.');
            }
            if(!$this->Guerrero_model->delete_message($message_id)){
                throw new Exception('No se pudo borrar el mensaje');
            }
            $this->session->set_flashdata('profile_success', 'El mensaje fue borrado exitosamente');
        }catch(Exception $e){
            $this->session->set_flashdata('profile_error', $e->getMessage());
            redirect('/power/perfil/' . $user_id);
        }
        redirect('/power/perfil/' . $user_id);
    }*/
 	public function delete_message()
 	{
 		$this->load->model('guerrero_model');
 		
 		$message_id = $this->uri->segment(4);
 		$user_id = $this->uri->segment(3);
 		
 		try{
 			if(!is_numeric($message_id)){
 				throw new Exception('El mensaje a borrar es invalido.');
 			}
 			if(!$this->guerrero_model->delete_message($message_id)){
 				throw new Exception('No se pudo borrar el mensaje');
 			}
 			$this->session->set_flashdata('profile_success', 'El mensaje fue borrado exitosamente');
 		}catch(Exception $e){
 			$this->session->set_flashdata('profile_error', $e->getMessage());
 			redirect('/power/perfil/' . $user_id);
 		}
 			redirect('/power/perfil/' . $user_id);
 			
 	}
 
 
 
	//----
	
	/**
	 * Users main page, list of users
	 */
	public function usuarios()
	{
		
		if (!$this->session->userdata ('logged_in'))
			$this->check_login();
			
		$this->load->helper('form');
		$this->load->library ('pagination');
		
		$view_data['page_title']       = 'Usuarios';
		$view_data['main_content']     = 'power/usuarios_view';

		$view_data['header_data']  = array ('page'=>$this->uri->segment(2), 'section'=>'usuarios');
		
		$db_names = array (
			'name'  => 'guerrero_real_name',
			'email'    => 'guerrero_email'
		);
		
			$user_page_select	=  $this->input->post('per_page');
			$list_order 		= $this->input->post('order_by');
			$guerrero_name		= $this->input->post('guerrero_name');
			//echo $list_order;
			if(!$this->session->userdata('page_limit'))
			{
				//echo "s";
				$this->session->set_userdata('page_limit',20);
			}
			
			if(!$this->session->userdata('table_order'))
			{
				$this->session->set_userdata('table_order', 'default');
			}
				
			
			if($user_page_select)
			{
				$this->session->set_userdata('page_limit', $user_page_select);
			}
			
			if($list_order)
			{
				$this->session->set_userdata('table_order', $list_order );
			}
			
			
							
			$per_page_limit= $this->session->userdata('page_limit');
			$order_by = $this->session->userdata('table_order');
			
			$search_args        = array();
			$current_search_url = site_url ('power/usuarios');
				
			for ($i = 3; $this->uri->segment ($i) && $this->uri->segment ($i) != 'pagina'; $i += 2)
			{
				$current_search_url .= '/'. $this->uri->segment ($i) .'/'. $this->uri->segment ($i + 1);
				$decoded_value       = urldecode ($this->uri->segment ($i + 1));
				$view_data['search_'.$this->uri->segment ($i)]    = $decoded_value;
				$search_args[$db_names[$this->uri->segment ($i)]] = $decoded_value;
			}
			
			if ($this->uri->segment ($i) != 'pagina' OR !$this->uri->segment ($i + 1))
				$page = 0;
			else {
				$page                  = $this->uri->segment ($i + 1);
				$config['uri_segment'] = $i + 1;
			}
			
			$current_search_url .= '/pagina/';
			$search              = $this->Guerrero_model->guerrero_search_backend ($search_args, $page, $per_page_limit, $order_by );
			
			$pagination_config['per_page']   = $per_page_limit;
			$pagination_config['cur_page']   = $page;
			$pagination_config['base_url']   = $current_search_url;
			$pagination_config['total_rows'] = $search->total;
			$pagination_config['display_pages']  = FALSE;
			$pagination_config['first_link'] = FALSE;
			$pagination_config['last_link']  = FALSE;
			$pagination_config['prev_tag_open']   = '<li class="previous_page_admin">';
			$pagination_config['prev_link']       = '&nbsp;';
			$pagination_config['prev_tag_close']  = '</li>';
			$pagination_config['next_tag_open']   = '<li class="next_page_admin">';
			$pagination_config['next_link']       = '&nbsp;';
			$pagination_config['next_tag_close']  = '</li>';
			
			$this->pagination->initialize ($pagination_config);
			
			$view_data['guerrero_list'] = $search->guerreros;
			
			$guerreros_ids = array();
		if($page == 0 )
		{
			$view_data['base_url'] = 	$current_search_url;
		
		}
		else
		{						
			$view_data['base_url'] = 	$current_search_url.$page;
		}

		$view_data['adv_search']   = TRUE;
		$view_data['current_pages'] = array(
											'inicial'	=> $page+1, 
											'final_' 	=> $page+$per_page_limit
											);
		$view_data['paginas'] = floor($search->total/$per_page_limit);
		$view_data['per_page'] = $per_page_limit;
		$view_data['order_by'] = $this->session->userdata('table_order');


		//$view_data['legion_list']  = $this->Guerrero_model->legions();
		//$view_data['rank_list']    = $this->Guerrero_model->ranks();
		//$view_data['country_list'] = $this->Guerrero_model->get_most_popular_places();
		//$view_data['new_ranks']    = $this->_rank_lightbox();

			
		$this->load->view('power/power_template', $view_data);

	}
	//----
	
	
	
	
	/**
	 * Users page, Top Users
	 */
	public function top_users()
	{
		
		if (!$this->session->userdata ('logged_in'))
			$this->check_login();
			
		$this->load->library ('pagination');
		
		$view_data['page_title']       = 'Top Users';
		$view_data['main_content']     = 'power/top_users_view';

		$view_data['header_data']  = array ('page'=>'usuarios', 'section'=>'topuser');
		
									
			$per_page_limit = 20;
			$order_by = 'friend_total DESC';
			$search_args        = array();
			
			$current_search_url = site_url ('power/top_users');
				
			$i = 3;			
			if ($this->uri->segment ($i) != 'pagina' OR !$this->uri->segment ($i + 1))
				$page = 0;
			else {
				$page                  = $this->uri->segment ($i + 1);
				$config['uri_segment'] = $i + 1;
			}
			
			$current_search_url .= '/pagina/';
			$search              = $this->Guerrero_model->guerrero_search_backend ($search_args, $page, $per_page_limit, $order_by );
			
			$pagination_config['per_page']   = $per_page_limit;
			$pagination_config['cur_page']   = $page;
			$pagination_config['base_url']   = $current_search_url;
			$pagination_config['total_rows'] = $search->total;
			$pagination_config['display_pages']  = FALSE;
			$pagination_config['first_link'] = FALSE;
			$pagination_config['last_link']  = FALSE;
			$pagination_config['prev_tag_open']   = '<li class="previous_page_admin">';
			$pagination_config['prev_link']       = '&nbsp;';
			$pagination_config['prev_tag_close']  = '</li>';
			$pagination_config['next_tag_open']   = '<li class="next_page_admin">';
			$pagination_config['next_link']       = '&nbsp;';
			$pagination_config['next_tag_close']  = '</li>';
			
			$this->pagination->initialize ($pagination_config);
			
			$view_data['guerrero_list'] = $search->guerreros;
			
			$guerreros_ids = array();
			
			foreach($view_data['guerrero_list'] as $g)
			{
				$guerreros_ids[] = $g->guerrero_id;
			}
			$view_data['messages'] = $this->Guerrero_model->count_guerreros_messages($guerreros_ids);
			$view_data['trophies'] = $this->Guerrero_model->count_guerreros_trophies($guerreros_ids, 'true');
		
		if($page == 0 )
		{
			$view_data['base_url'] = 	$current_search_url;
		
		}
		else
		{						
			$view_data['base_url'] = 	$current_search_url.$page;
		}

		
		$view_data['current_pages'] = array(
											'inicial'	=> $page+1, 
											'final_' 	=> $page+$per_page_limit
											);
		$view_data['paginas'] = floor($search->total/$per_page_limit);
		$view_data['per_page'] = $per_page_limit;
			
		$this->load->view('power/power_template', $view_data);

	}
	//-----
	
	public function dinero()
	{
		
		if (!$this->session->userdata ('logged_in'))
			$this->check_login();
		
		$this->load->helper('form');
		$this->load->library ('pagination');
		
		
		
		
		$view_data['page_title']       = 'Dinero Recolectado';
		$view_data['main_content']     = 'power/dinero_view';
		$view_data['num_of_guerreros'] = 0;
		$view_data['raised_money']     = 0;
		
		$view_data['header_data']  = array ('page'=>$this->uri->segment(1), 'section'=>'dinero');
		$search_args        = array('guerrero_is_test'=> 0);
		$page = 0;
		$order_by = 'guerrero_created DESC';
		$per_page_limit = 20;
		
		$money = $this->Guerrero_model->get_first_last_money();
		//$all_money = $money;
		//$last_money_value = array_pop($money);
		
		
		/*
foreach ($breakdown as $subscription) {
			if($subscription->subscription_type_id == 1 )
			{
				$fee =  $subscription->subscription_type_fee;
			}
			else{
				$datetime2 = new DateTime("now");	
				$datetime1 = new DateTime($subscription->guerrero_created);
				$interval =date_diff($datetime1, $datetime2);
				$meses = $interval->m;
				$meses = $meses +1;
				$fee = $subscription->subscription_type_fee * $meses;
			}
			
			$view_data['raised_money'] +=$fee;
			//echo $subscription->guerrero_created;
		}
*/		
		$datetime2 = new DateTime($money[1]->day_date);	
		$datetime1 = new DateTime($money[0]->day_date);
		$interval = date_diff($datetime1, $datetime2);

		$weeks = date('W', strtotime($money[1]->day_date));
		$months = $interval->m;
		$days = $interval->days;
		
		$view_data['raised_money'] = $money[1]->total_money;
		$view_data['raised_by_month'] = ($money[1]->total_money)/$months;
		$view_data['raised_by_week'] = ($money[1]->total_money)/$weeks;
		$view_data['raised_by_day'] = ($money[1]->total_money)/$days;

		
		
		$current_search_url = site_url ('power/dinero');
				
			for ($i = 3; $this->uri->segment ($i) && $this->uri->segment ($i) != 'pagina'; $i += 2)
			{
				$current_search_url .= '/'. $this->uri->segment ($i) .'/'. $this->uri->segment ($i + 1);
				$decoded_value       = urldecode ($this->uri->segment ($i + 1));
				$view_data['search_'.$this->uri->segment ($i)]    = $decoded_value;
				$search_args[$db_names[$this->uri->segment ($i)]] = $decoded_value;
			}
			
			if ($this->uri->segment ($i) != 'pagina' OR !$this->uri->segment ($i + 1))
				$page = 0;
			else {
				$page                  = $this->uri->segment ($i + 1);
				$config['uri_segment'] = $i + 1;
			}
			
			$current_search_url .= '/pagina/';

		
		$guerreros = $this->Guerrero_model->guerrero_search_backend ($search_args, $page, $per_page_limit, $order_by );
			
			$pagination_config['per_page']   = $per_page_limit;
			$pagination_config['cur_page']   = $page;
			$pagination_config['base_url']   = $current_search_url;
			$pagination_config['total_rows'] = $guerreros->total;
			$pagination_config['display_pages']  = FALSE;
			$pagination_config['first_link'] = FALSE;
			$pagination_config['last_link']  = FALSE;
			$pagination_config['prev_tag_open']   = '<li class="previous_page_admin">';
			$pagination_config['prev_link']       = '&nbsp;';
			$pagination_config['prev_tag_close']  = '</li>';
			$pagination_config['next_tag_open']   = '<li class="next_page_admin">';
			$pagination_config['next_link']       = '&nbsp;';
			$pagination_config['next_tag_close']  = '</li>';
			
			$result = $this->pagination->initialize ($pagination_config);
			echo $result;
		$subscription_type = $this->Guerrero_model->subscription_types();
		
		$view_data['current_pages'] = array(
											'inicial'	=> $page+1, 
											'final_' 	=> $page+$per_page_limit
											);
		$view_data['paginas'] = floor($guerreros->total/$per_page_limit);
		$view_data['guerrero_list'] = $guerreros->guerreros;
		$view_data['subscriptions'] = $subscription_type;
		$this->load->view('power/power_template', $view_data);
	}
	
	
	//money stats ajax call 
	public function money_stats_ajax()
	{
		header ('Content-type: application/json');
		
		$spanish_months = array('enero', 'feb.', 'marzo',
		 'abr.', 'mayo' , 'jun.', 'jul.', 'agosto', 'sept.','oct.','nov.','dic.');
		
		try {
			$last_seven_days_stats = $this->Guerrero_model->get_last_seven_money_entries();
			
			if (!$last_seven_days_stats )
				throw new Exception;
			$data = array();
			foreach ( $last_seven_days_stats as $stats )
			{
				$dia = date('d',strtotime($stats->day_date));
				$mes = date('n',strtotime($stats->day_date));

				$year = date('Y',strtotime($stats->day_date));
				$data[] = array(
								'id'     => $stats->id,
								'amount' => $stats->per_day,
								'dia'    => $dia,
								'mes'    => $mes,
								'year'	 => $year
								);
			}
						
			echo json_encode (array ('response'=>'success', 'seven_days_stats'=>$data));
		}
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}
		
	}
	
	// get confirmed payments from payflow and inserts to db. 
	public function from_payflow_toDB()
	{
		$this->load->library ('Payflow');
		$last_counts = $this->Guerrero_model->get_last_seven_money_entries();
		// setup payflow variables 
		$this->payflow->PARTNER  = 'verisign';
		$this->payflow->VENDOR   = 'rmfoundation';
		$this->payflow->USER     = 'guerreros';
		$this->payflow->PWD      = '00guerreroscash00';
		$this->payflow->environment = 'live';
	 	
	 	
	 	$date = date("Y-m-d", strtotime("-1 day"));
	 	//$date =   date('Y-m-d');
	 	$money = array(
	 		 'per_day' => 0, 
	 		 'total_money' => $last_counts[6]->total_money
	 	);
	 	/* this for is for manually update 
for ($i = 0; $date != '2012-04-04'; $i++)
	 	{
*/	
	 	
	 			
		$parameters = array(
	 					'start_date'=> $date.' 00:00:00',
	 					'end_date'	=> $date.' 23:59:59'
	 				);
	 				
		$response = $this->payflow->run_report_request('CustomReport', $parameters);
	 	
	 	$responseObj = $this->payflow->parse_xml_response($response);
		$reportId = $responseObj->runReportResponse->reportId;
		
		$response = $this->payflow->get_result_request($reportId);
		
		$response = $this->payflow->get_metadata_request($reportId);
		
		$responseObj = $this->payflow->parse_xml_response($response);
		
		$fields = $this->payflow->generate_field_array($responseObj);
		
		$response = $this->payflow->get_data_request($reportId);
		
				//print_r($response);		
		$responseObj = $this->payflow->parse_xml_response($response);
			//print_r($responseObj);
			
		if ($responseObj->baseResponse->responseMsg =='Request has completed successfully')
		{
			
			$data = $this->payflow->generate_data_array($responseObj, $fields);
		
	
			 foreach( $data as $t)
			 {
			 	//echo $t['Recurring'];
			 	if($t['Transaction State'] == 9 && (int)$t['Amount']/100 == 2) //usar los tres amounts 
			 	{
			 		//echo ((int)$t['Amount'])/100 . '<br>';
			 		$money['per_day'] += ((int)$t['Amount'])/100;
			 	}
			 	else if(((int)$t['Amount']/100 == 10 || (int)$t['Amount']/100 == 25 || (int)$t['Amount']/100 == 50 ) && $t['Transaction State'] == 8) 
			 	{
			 	$money['per_day'] += ((int)$t['Amount'])/100;
			 	}
			 }//end data foreach
			 
		 }
		 else{
		 	
		 	$money['per_day'] = 0;
		 }
		 $money['total_money'] += $money['per_day'];
		 $money['day_date'] = $date;
		 
		 if(!$this->Guerrero_model->insert_money($money))
		 {
		 	echo "error insertando";
		 } 
		 else
		 {
		 	echo "No problemo";
		 
		 } 
		
		//reset money per day
		//$money['per_day'] = 0;
		
		//increment date 	 	
/*
	 	$date = strtotime($date); //assuming it's not a timestamp\
		$date = $date + (60 * 60 * 24); //increase date by 1 day
		$date =  date('Y-m-d', $date);
*/
		
		
		
		//}//end perday for

	
	
	}
	
	
	
	/**
	 * Payflow Authentication Cleanup
	 */
	public function payflow_auth_cleanup()
	{
		try {
			if (!$this->input->is_cli_request())
				throw new Exception;
			
			try {
				$this->load->library ('Payflow');
				
				if (!$old_auths = $this->Guerrero_model->old_authorizations())
					throw new Exception;
				
				foreach ($old_auths as $auth) {
					$this->payflow->TRXTYPE  = 'V';	                // V = Void transaction type
					$this->payflow->TENDER   = 'C';                 // C = Credit card transaction
					$this->payflow->PARTNER  = 'verisign';
					$this->payflow->VENDOR   = 'rmfoundation';
					$this->payflow->USER     = 'guerreros';
					$this->payflow->PWD      = '00guerreroscash00';
					$this->payflow->ORIGID   = $auth->auth_pnref;        // Authorization PNREF
					$this->payflow->COMMENT1 = 'Void automatico de autorizaciones viejas.';
					
					try {
						$this->payflow->process();
						$voided_auths[] = $auth->auth_pnref;
					}
					catch (Exception $e) {
						continue;
					}
				}
				
				if (isset ($voided_auths)) {
					if (!$this->Guerrero_model->delete_authorizations ($voided_auths))
						throw new Exception;
					
					echo 'SUCCESS -> Voided ', count ($voided_auths), ' authorizations.';
				}
			}
			catch (Exception $e) {
				echo 'FAILURE';
			}
		}
		catch (Exception $e) {
			show_404();
		}
	}
	
	
	//----
	
	/**
	 * Migrate Ticker
	 *
	 * Migrates data from old ticker table (and other assorted tables) to new ticker table
	 */
	public function migrate_ticker()
	{
		if (!$this->input->is_cli_request()) {
			show_404();
			return;
		}
		
		try {
			$item_types = array ('registration', 'achievement', 'invitation', 'rank');
			$stamp      = strtotime ('today 12:00pm');
			
			foreach ($item_types as $type) {
				$offset = 0;
				
				while ($old_ticker = $this->Guerrero_model->old_ticker ($type, $offset)) {				
					foreach ($old_ticker as $ticker_item) {
						$new_ticker_items[] = $this->Guerrero_model->format_new_ticker ($type, $ticker_item, $stamp);
						$stamp++;
					}
					
					if (!$this->Guerrero_model->write_new_ticker ($new_ticker_items))
						throw new Exception ($type);
					
					$old_count = count ($old_ticker);
					unset ($old_ticker);
					unset ($new_ticker_items);
					
					if ($old_count < 1000)
						break;
					
					$offset += 1000;
				}
			}
			
			echo 'Success';
		}
		
		catch (Exception $e) {
			echo 'Error migrating ' . $e->getMessage();
		}
	}
	//----
	/**
	 *
	 *Activar/Desactivar/Bloquear/Eliminar
	 *
	 */	
	
	
}
