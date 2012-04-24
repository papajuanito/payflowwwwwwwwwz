<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social extends CI_Controller
{
	// !Class Variables
	//------------------------------------------
	
	var $body_id  = 'social';
	var $guerrero = NULL;
	
	
	
	// !Public Methods
	//------------------------------------------
	
	/**
	 * Contructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		date_default_timezone_set('America/Los_Angeles');

		
		//$this->output->enable_profiler (TRUE);
		
		$this->lang->load ('social');
		$this->load->model ('Guerrero_model');
		
		$guerrero_id    = $this->session->userdata ('so_guerrero_id');
		$this->guerrero = empty ($guerrero_id) ? $this->guerrero : $this->Guerrero_model->guerreros ($guerrero_id);
		
		if (empty ($this->guerrero))
			redirect ('home');
	}
	
	
	//----
	
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		$this->load->helper ('form');
		
		$view_data['main_content']  = 'social/cuartel_view';
		$view_data['page_title']    = lang ('app_dashboard_title');
		$view_data['email_success'] = $this->session->flashdata ('cu_email_success');
		$view_data['new_ranks']     = $this->_rank_lightbox();
					$view_data['notifications_list']   = $this->Guerrero_model->notifications_by_guerrero ($this->guerrero, 2);

		
	    try {
	    	$view_data['message_list']         = $this->Guerrero_model->messages_by_guerrero ($this->guerrero->guerrero_id);
	    	$view_data['last_message']         = end ($view_data['message_list']);
		    $view_data['more_friends']         = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		    $view_data['recommended_warriors'] = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 2);
			$view_data['recompensas'] 	       = $this->Guerrero_model->trophies_by_guerrero($this->guerrero->guerrero_id);

	    }
	    
	    catch (Exception $e) {}
	    
	    $this->load->view ('template.php', $view_data);
	}
	
	
	//----
	
	
	/**
	 * Notifications Controller.
	 */
	public function notifications()
	{
		$this->load->helper ('date');
		
		$view_data['main_content']         = 'social/notifications_view';
		$view_data['page_title']           = 'Notificaciones';
		$view_data['notifications_list']   = $this->Guerrero_model->notifications_by_guerrero ($this->guerrero);
		$view_data['last_notification']    = end ($view_data['notifications_list']);
		$view_data['last_registration']    = (object) array ('ticker_date' => '');
		$view_data['more_friends']         = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		$view_data['recommended_warriors'] = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 2);
		$view_data['recompensas']          = $this->Guerrero_model->trophies_by_guerrero ($this->guerrero->guerrero_id);
		$view_data['new_ranks']            = $this->_rank_lightbox();
		
		$this->load->view ('template.php', $view_data);
	}
	
	//----
	
	/**
	 * AJAX More Notifications
	 */
	public function ajax_more_notifications()
	{
		$this->load->helper ('date');
		
		header ('Content-type: application/json');
		
		try {
			$last_checked_date      = $this->input->post ('last_checked_date');
			$last_registration_date = $this->input->post ('last_registration_date');
			
			if (!$notifications_list = $this->Guerrero_model->notifications_by_guerrero ($this->guerrero, 6, $last_checked_date, $last_registration_date))
				throw new Exception;
			
			foreach ($notifications_list as $notification)
				$notification->ticker_long_date = long_date ($notification->ticker_date_stamp);
			
			echo json_encode (array ('response'=>'success', 'notifications'=>$notifications_list));
		}
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}
	}
	
	//----
	
	
	/**
	 * Recompensas Controller.
	 */
	 public function recompensas()
	 {
	 	
	 	$view_data['main_content'] = 'social/recompensas_view';
		$view_data['page_title']   = 'Recompensas';
		$view_data['recompensas_default'] = $this->Guerrero_model->trophies($this->guerrero->guerrero_id);
		$view_data['new_ranks']    = $this->_rank_lightbox();

		$this->load->view ('template.php', $view_data);
	 }
	 
	 /**
	 * Mis Rangos Controller.
	 */
	 public function mis_rangos()
	 {
		$view_data['main_content']         = 'social/misrangos_view';
		$view_data['page_title']           = 'Mis Rangos';
		$view_data['more_friends']         = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		$view_data['recommended_warriors'] = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 2);
		
		$view_data['rank_data']       = $this->Guerrero_model->rank_by_id ($this->guerrero->guerrero_rank_id);
		$view_data['next_rank_data']  = $this->Guerrero_model->guerrero_next_rank($this->guerrero->guerrero_rank_id);
		$view_data['completed_ranks'] = $this->Guerrero_model->guerrero_completed_ranks($this->guerrero->guerrero_rank_id);
		$view_data['legion_data']     = $this->Guerrero_model->guerrero_legion($this->guerrero->guerrero_legion_id);
		$view_data['new_ranks']       = $this->_rank_lightbox();
		$view_data['recompensas'] 	  = $this->Guerrero_model->trophies_by_guerrero($this->guerrero->guerrero_id);

		
		$numOfPoints      = $this->guerrero->friend_total;
		
		if (!empty ($view_data['next_rank_data'])) {
			$pointsToNextRank = $view_data['next_rank_data']->rank_requirement;
			$percentCompleted = ($numOfPoints / $pointsToNextRank) * 100;
			$view_data['percent'] = $percentCompleted;
			
			$friends_left = $pointsToNextRank - $numOfPoints;
			$view_data['friends_left'] = $friends_left;
		}
		
		$this->load->view ('template.php', $view_data);
	 }
	
	//----
	
	/**
	 * Profile Controller.
	 */
	public function perfil ($guerrero_id = 0)
	{
		$this->load->helper ('form');
		
		$view_data['main_content']      = 'social/perfil_view';
		//$view_data['more_friends']      = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		$view_data['page_title']        = lang ('app_profile_title');
		$view_data['mywarriors']        = TRUE;
    	
    	try{
			if (empty ($guerrero_id)
				OR !is_numeric ($guerrero_id)
				OR !$guerrero = $this->Guerrero_model->guerreros ($guerrero_id))
					throw new Exception;
			
			$view_data['guerrero']      = $guerrero;
			$view_data['msg_recipient'] = $guerrero->guerrero_id;
			
			$view_data['friendship']	= $this->Guerrero_model->friend_status($this->guerrero->guerrero_id, $guerrero->guerrero_id);
		}
    	
    	catch (Exception $e){
    		// Defaults to current user's profile
    		$view_data['guerrero'] = $this->guerrero;
    	}
    	
    	$view_data['subscription_type'] = $this->Guerrero_model->subscription_type_by_id ($view_data['guerrero']->guerrero_subscription_type_id);
    	
    	try {
			$view_data['message_count'] = $this->Guerrero_model->message_count_by_guerrero ($view_data['guerrero']->guerrero_id);
			$view_data['message_list']  = $this->Guerrero_model->messages_by_guerrero ($view_data['guerrero']->guerrero_id, 'timeline');
			$view_data['last_message']  = end ($view_data['message_list']);
			$view_data['friend_list']   = $this->Guerrero_model->guerrero_friends ($view_data['guerrero']->guerrero_id, 'sent', 'accepted');
			$view_data['recompensas'] 	= $this->Guerrero_model->trophies_by_guerrero($view_data['guerrero']->guerrero_id);
			$view_data['new_ranks']     = $this->_rank_lightbox();
    	}
    	catch (Exception $e) {}
    	
    	$this->load->view ('template.php', $view_data);
	}
	
	//----



	/**
	 * Get Waypoint ajax method
	 */

	public function get_guerrero_waypoint($id)
	{
		$this->load->model('Guerrero_model');

		header ('Content-type: application/json');

		try {
			
			if( $this->guerrero->guerrero_id == $id )
			{
				$guerreros = $this->Guerrero_model->guerreros ($id);	
			}
			else
			{		
				$guerreros = $this->Guerrero_model->guerreros ($id, true);
			}
			
			
			if (!$guerreros && !is_array ($guerreros))	// We only check for DB errors
				throw new Exception();			// no content errors are handled client-side
			
			
			$response_array['response']		= 'success';
			$response_array['guerreros']	= $guerreros;
			echo json_encode ($response_array);
		}
		
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}

	}

	//----


	/**
	 * Get NEW Trophies ajax method
	 */
	public function get_new_trophies()
	{
		$this->load->model('Guerrero_model');

		header ('Content-type: application/json');
		
		try{
			
			$new_trophies = $this->Guerrero_model->new_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_real_name, $this->guerrero->guerrero_email);
			
			
			
			/*
if(!$new_trophies && !is_array($new_trophies));
				throw new Exception();
*/			
			$response_array['response'] = 'success';
			$response_array['new_trophies'] =$new_trophies;
			$response_array['description'] = array(
					'gue' 	=>  '¡Eres un guerrero ejemplar! Tu compromiso y esfuerzo con la lucha te hacen ser un Guerrero Dedicado. Continua luchando y erradiquemos la trata.',
					'reclu'	=>  '¡Tu especialidad es aumentar las filas del ejercito de luz! Continua tu misión expandiendo la lucha como Guerrero Reclutador.',
					'pens' 	=>  'Eres inteligente e introspectivo, y tu prioridad es educar gritar contra la trata, por esto eres un Guerrero Pensador.',
					'amigo'	=>  'Luchar a tu lado es un honor. Tu valentía y liderazgo de hacen un futuro guerrero de luz, por esto eres un Guerrero Mejor Amigo.',
					'super'  =>  '¡Eres un luchador incansable! Tus ejecuciones inspira el paso de nuestro ejercito en contra de las fuerzas de mal. Te destacas como un Súper Guerrero.',
					'foto'  	=>  'Tu imagen de luz ilumina y destruye las fuerzas del mal. Continua luchando como Guerrero Fotogénico.',
					'soc' 	=>  'No pierdes ninguna oportunidad de gritar nuestro mensaje de amor. Sigue la lucha contra las fuerzas del mal como Guerrero Sociable.'
			);
			
			echo json_encode ($response_array);
		}
		
		catch (Exception $e)
		{
			echo json_encode(array ('response'=>'error'));
		}
		

	}
	
	/**
	 * Update Social Trophy ajax method
	 */
	public function get_social_trophy()
	{
		header ('Content-type: application/json');
		try{
		
			$value = $this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'soc');
			if(!$value)
			throw new Exception();	
			
			echo json_encode (array('response'=>'success'));
		}
		catch (Exception $e)
		{		
			echo json_encode(array ('response'=>'error'));
		}
			
	}
	

	/**
	 * List of warrior friends
	 */

	public function my_warriors()
	{
		$view_data['page_title']   = lang ('so_guerreros_title');
		$view_data['main_content'] = 'social/warriors_list_view';
		$view_data['friend_list']  = $this->Guerrero_model->guerrero_friends ($this->guerrero->guerrero_id, 'sent', 'accepted');
		$view_data['request_list'] = $this->Guerrero_model->guerrero_friends ($this->guerrero->guerrero_id, 'received', 'pending', 3);
		$view_data['more_friends'] = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		$view_data['pending_invs'] = TRUE;
		$view_data['new_ranks']    = $this->_rank_lightbox();
		
		$this->load->view('template.php', $view_data);
	}

	//----



	/**
	 * User search to add as friends (GUERREROS DEL MUNDO)
	 */

	public function users_search()
	{
		$this->load->library ('pagination');
		
		if (!$this->uri->segment (3)) {
			$view_data['page_title']    = lang ('app_mundo_title');
			$view_data['main_content']  = 'social/top_guerreros_view';
			$view_data['guerrero_list'] = $this->Guerrero_model->guerreros_top (10);
			
			$guerreros_ids = array();
			
			foreach($view_data['guerrero_list'] as $g)
			{
				$guerreros_ids[] = $g->guerrero_id;
			}
			
			$view_data['trophies'] = $this->Guerrero_model->count_guerreros_trophies($guerreros_ids);
		}
		else
		{
			$db_names = array (
				'legion'  => 'legion_id',
				'rank'    => 'guerrero_rank_id',
				'country' => 'guerrero_map_country'
			);
			
			$search_args        = array();
			$current_search_url = site_url ('social/users_search');
				
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
			$search              = $this->Guerrero_model->guerrero_search ($search_args, $page);
			
			$pagination_config['per_page']   = 10;
			$pagination_config['cur_page']   = $page;
			$pagination_config['base_url']   = $current_search_url;
			$pagination_config['total_rows'] = $search->total;
			
			$this->pagination->initialize ($pagination_config);
			
			$view_data['page_title']    = lang ('so_search_title');
			$view_data['main_content']  = 'social/users_search_view';
			$view_data['guerrero_list'] = $search->guerreros;
			
			$guerreros_ids = array();
			
			foreach($view_data['guerrero_list'] as $g)
			{
				$guerreros_ids[] = $g->guerrero_id;
			}
			
			$view_data['trophies'] = $this->Guerrero_model->count_guerreros_trophies($guerreros_ids);

		}
		
		$view_data['adv_search']   = TRUE;
		$view_data['legion_list']  = $this->Guerrero_model->legions();
		$view_data['rank_list']    = $this->Guerrero_model->ranks();
		$view_data['country_list'] = $this->Guerrero_model->get_most_popular_places();
		$view_data['new_ranks']    = $this->_rank_lightbox();
		
		$this->load->view('template.php', $view_data);
	}
	
	//----

	/**
	 * Invite Friend
	 * - AJAX method
	 */
	public function ajax_invite_friend ($friend_id = 0)
	{
		header ('Content-type: application/json');
		
		try {
			if (empty ($friend_id))
				throw new Exception ('general');
			
			$friend_status = $this->guerrero->guerrero_id == $friend_id ?
				'self' :
				$this->Guerrero_model->friend_status ($this->guerrero->guerrero_id, $friend_id);
			
			switch ($friend_status) {
				default:                 // Unrecognized status
				case 'model_error':      // DB error
					throw new Exception;
					break;
				case 'self':             // Trying to add yourself
					$return_array['type'] = 'self';
					break;
				case 'friends':          // Already friends
					$return_array['type'] = 'already_friends';
					break;
				case 'waiting_on_them':  // You sent sent an invite already
				case 'they_ignored':     // They ignored you, treat as if sent invite is still pending
					$return_array['type'] = 'invite_pending';
					break;
				case 'you_ignored':      // You ignored them… douche bag
				case 'waiting_on_you':   // They sent you an invite… accept it
					$return_array['type'] = 'accept';
					$check_rank           = TRUE;
					break;
				case 'no_invites':       // No invite yet… let's invite
					$return_array['type'] = 'invite';
					break;
			}

			
			if (in_array ($return_array['type'], array ('accept', 'invite'))
				&& !$this->Guerrero_model->write_friends ($this->guerrero->guerrero_id, $friend_id, $return_array['type']))
					throw new Exception;
			
			if (isset ($check_rank) && $check_rank) {
				if ($friend = $this->Guerrero_model->guerreros ($friend_id))
					$this->_check_rank_up ($friend);
				$this->_check_rank_up ($this->guerrero);
			}
			
			$return_array['response'] = 'success';
			echo json_encode ($return_array);
		}
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}
	}

	//----
	
	/**
	 * Centro de Reclutamiento
	 */
	public function reclutamiento()
	{
		$this->load->helper ('form');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('twitteroauth');
		
						
		$view_data['main_content'] = 'social/reclutamiento_view';
		$view_data['page_title']   = 'Centro de Reclutamiento';
		$view_data['recommended']  = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 12);
		$view_data['new_ranks']    = $this->_rank_lightbox();
		$view_data['consumer_key'] = 'FYsfXE5zLY6vcVKTHfs1Q';
		$view_data['consumer_secret'] = 'ljQOwDBQZzomn2VKVF4znLZNiK8CC6pfUD5rkA64KnQ';
		$view_data['oauth_token']  = $this->session->userdata('oauth_token');
		$view_data['oauth_token_secret']  = $this->session->userdata('oauth_token_secret');
		$this->load->view ('template.php', $view_data);
	}
	//-----
	
	
	/**
	 * Function para procesar la authorización de twitter
	*/
	public function twitter_oauth_process()
	{
		
		$this->load->library('twitteroauth');
	
		$request_token = $this->twitteroauth->getRequestToken(site_url("social/reclutamiento"));
		
		$this->session->set_userdata('oauth_token', $request_token['oauth_token']);
		$this->session->set_userdata('oauth_token_secret', $request_token['oauth_token_secret']);
	
		if($this->twitteroauth->http_code==200){  
			$url = $this->twitteroauth->getAuthorizeURL($request_token['oauth_token']); 
    		redirect( $url); 
		}
				
	}
	//----
	
	/**
	 * Function para enviar mensajes directos por twitter.. 
	*/
	public function twitter_send_message()
	{
		header ('Content-type: application/json');
		$this->load->library('twitteroauth');
		
		$twitter_user_id = $this->uri->segment(3);

		$access_token = $this->session->userdata('access_token');

		$twitteroauth = new TwitterOAuth('FYsfXE5zLY6vcVKTHfs1Q', 'ljQOwDBQZzomn2VKVF4znLZNiK8CC6pfUD5rkA64KnQ', $access_token['oauth_token'] , $access_token['oauth_token_secret'] );
		
		$value = $twitteroauth->post('direct_messages/new', array('user_id' => $twitter_user_id, 'text' =>"Únete a mi ejercito en Guerreros de Luz, una comunidad guerreros en contra de la trata humana. http://guerrerosdeluz.org/ via @RM_Foundation"));
		if(isset($value->id_str))
		{
			echo json_encode (array ('response'=>'success', 'obj'=> $value));
		}
		else
		{	
			echo json_encode (array ('response'=>'error'));		
		}
	}
	
	
	/**
	 * Send email invites
	 */
	public function send_email_invites()
	{
		try {
			$this->load->helper ('email');
			$this->load->library ('email');
			
			$email_from      = $this->input->post ('email_from');
			$email_subject   = $this->input->post ('email_subject');
			$email_message   = $this->input->post ('email_message');
			$email_addresses = $this->input->post ('emails');
			$good_addresses  = array();
			
			foreach ($email_addresses as $address)
				if (!empty ($address) && valid_email ($address))
					$good_addresses[] = $address;
			
			if (empty ($good_addresses) OR empty ($email_from))
				throw new Exception;
			
			$this->email->initialize (array ('mailtype'=>'text'));
			$this->email->subject ($email_subject);
	        $this->email->message ($email_message . PHP_EOL . PHP_EOL . 'Accede a https://guerrerosdeluz.org/');
			$this->email->from ($this->guerrero->guerrero_email, $email_from);
			$this->email->to ($good_addresses);
			$result = $this->email->send();
			
			if (!$result)
				throw new Exception;
			
			$this->session->set_flashdata ('soc_reclutamiento_success', 'Tus invitaciones por email fueron enviadas exitosamente.');
		}
		
		catch (Exception $e) {
			$this->session->set_flashdata ('soc_reclutamiento_error', 'Hubo un error enviando las invitaciones por email. Intenta nuevamente.');
		}
		
		redirect ('social/reclutamiento');
	}

	
	//----
	
	
	/**
	 * Pending Invitations in 
	 */
	public function pending_invs()
	{
		$view_data['page_title']       = 'Invitaciones Pendientes';
		$view_data['main_content']     = 'social/pending_invs_view';
		$view_data['request_list']     = $this->Guerrero_model->guerrero_friends ($this->guerrero->guerrero_id, 'received', 'pending');
		$view_data['more_friends']     = $this->Guerrero_model->recommended_guerreros ($this->guerrero->guerrero_id, 20);
		$view_data['recompensas'] 	   = $this->Guerrero_model->trophies_by_guerrero($this->guerrero->guerrero_id);
		$view_data['new_ranks']        = $this->_rank_lightbox();
		
		$this->load->view('template.php', $view_data);
	}
	
	/**
	 * Configuracion
	 */
	 public function configuracion()
	 {
	 	$this->load->helper ('form');
	 	$this->load->helper ('file');
		$this->lang->load ('app');
		$this->load->model ('Guerrero_model');

	 	
	 	$view_data['main_content']     = 'social/configuracion_view';
		$view_data['page_title']       = 'Configuracion';
		$view_data['form_action']      = 'social/update_configuracion';
		$view_data['legion_list']      = $this->Guerrero_model->legions();
		$view_data['selected_country'] = $this->guerrero->guerrero_country;
		$view_data['default_avatars']  = get_dir_file_info ('img/avatars/');
		$view_data['i']                = 1;  // loop counter for default avatars
		$view_data['new_ranks']        = $this->_rank_lightbox();
		
		$this->load->view ('template.php', $view_data);
	 }
	
	//----
	
	public function update_configuracion()
	{
		try {
			switch ( $this->input->post('configuration_step')) {
			case 'datos':
				$guerrero_updated = array (
						
						'guerrero_name'                 => $this->input->post('guerrero_name'),
						'guerrero_legion_id'			=> $this->input->post('guerrero_legion_id'),
						'recovery'						=> NULL
				);
			
				break;
			case 'profile':
				
				if( $this->input->post('public_name') )
				{
					$name_bool = 1;
				}
				else
				{
					$name_bool = 0;
				}
				
				if( $this->input->post('public_town') ){
					$town_bool = 1;
				}
				else{
					$town_bool = 0;

				}
			
				$guerrero_updated = array (
						
						'guerrero_name'                 => $this->input->post('warrior_nick'),
						'guerrero_real_name'            => $this->input->post('warrior_name'),
						'guerrero_is_name_private'      => $name_bool,
						'guerrero_address_line1'        => $this->input->post('warrior_address'),
						'guerrero_town'                 => $this->input->post('warrior_town'),
						'guerrero_is_loc_private'		=> $town_bool,
						'guerrero_country'              => $this->input->post('country'),
						'guerrero_zip'                  => $this->input->post('warrior_zip'),
						'guerrero_phone'                => $this->input->post('warrior_phone'),
						'guerrero_birthday'             => $this->input->post('guerrero_birthday'),
						'recovery'						=> NULL
				);
				break;
			case 'location':
				$guerrero_updated = array (				
							'recovery'						=> NULL,
							'guerrero_map_town'             => $this->input->post('country_search'),
							'guerrero_map_country'          => trim (end (explode (',', $this->input->post('country_search')))),
							'guerrero_geo_lat'              => $this->input->post('lat_value'),
							'guerrero_geo_long'             => $this->input->post('long_value')
				);
				break;
			case 'avatar' :
				$this->load->library ('upload');
				$this->load->library ('image_lib');
				
				$guerrero_updated = array (				
					'recovery'        => NULL,
					'guerrero_avatar' => $this->input->post ('pre_loaded_image')
				);
				
				if ($this->input->post ('avatar_reset')) {
					$reset = TRUE;
					$guerrero_updated['guerrero_avatar'] = NULL;
				}
				
				if ($_FILES['uploaded_avatar']['error'] != UPLOAD_ERR_NO_FILE)
				{
					try {
						$upload_config = array (
							'upload_path'	=> 'uploads/avatars/',
							'allowed_types'	=> 'jpeg|jpg|png|gif',
							'max_size'		=> 10240	// 10 MB = 10 * 1024 kB = 10240 kB
						);
						
						$this->upload->initialize ($upload_config);
						
						if (!$upload_result = $this->upload->do_upload ('uploaded_avatar'))
							throw new Exception ($this->upload->display_errors());
						
						$default_width  = 243;
						$default_height = 264;
						$default_ratio  = $default_width / $default_height;
						$upload_info    = $this->upload->data();
						$current_ratio  = $upload_info['image_width'] / $upload_info['image_height'];
						
						// Crop if image has different ratio (default size 243 x 264, 81:88 aspect ratio)
						if ($current_ratio != $default_ratio)
						{
							$image_config['source_image'] = $upload_info['full_path'];
							
							if ($current_ratio > $default_ratio) {
								$image_config['width']       = floor ($upload_info['image_height'] * $default_ratio);
								$image_config['x_axis']      = floor (($upload_info['image_width'] - $image_config['width']) / 2);
								$upload_info['image_width']  = $image_config['width'];
							}
							else {
								$image_config['height']      = floor ($upload_info['image_width'] * ($default_height / $default_width));
								$image_config['y_axis']      = floor (($upload_info['image_height'] - $image_config['height']) / 2);
								$upload_info['image_height'] = $image_config['height'];
							}
							
							$this->image_lib->initialize ($image_config);
							
							if (!$this->image_lib->crop())
								throw new Exception ($this->image_lib->display_errors());
						}
					
						// Resize if image is too large
						if ($upload_info['image_width'] > $default_width OR $upload_info['image_height'] > $default_height)
						{
							$image_config                 = array();
							$image_config['source_image'] = $upload_info['full_path'];
							$image_config['width']        = $default_width;
							$image_config['height']       = $default_height;
							
							$this->image_lib->initialize ($image_config);
							
							if (!$this->image_lib->resize())
								throw new Exception ($this->image_lib->display_errors());
						}
						
						$guerrero_updated['guerrero_avatar'] = $upload_info['file_name'];
						
						//Recompensa FOTOGENICO
						$this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'foto');
					}
					
					catch (Exception $e) {
						throw $e;
					}
				}
				
				if (!isset ($reset) && empty ($guerrero_updated['guerrero_avatar']))
					throw new Exception ('No seleccionó ni subió un avatar.');
				
				break;
			}
			
			if (!$this->Guerrero_model->update_guerrero ($guerrero_updated, $this->guerrero->guerrero_id))
				throw new Exception ('Hubo un error actualizando tu configuración. Intenta nuevamente.');
			
			if (isset ($upload_info) OR isset ($reset))  // Delete old avatar if uploaded new or reset
				@unlink ('uploads/avatars/' . $this->guerrero->guerrero_avatar);
			
			$this->session->set_flashdata ('so_config_success', 'Tu configuración fue actualizada exitosamente.');
		}
		
		catch (Exception $e) {
			if (isset ($upload_info))		// Delete uploaded file in case processing or DB update fails
				@unlink ($upload_info['full_path']);
			
			$this->session->set_flashdata ('so_config_error', $e->getMessage());
		}
		
		redirect ('social/configuracion');
	}
	
	
	public function ajax_handle_friend ($friend_id = 0, $status = 'pending')
	{
		header ('Content-type: application/json');
		
		try {
			if (empty ($friend_id) OR !is_numeric ($friend_id)
				OR empty ($status) OR !is_string ($status))
					throw new Exception;
			
			switch ($status) {
			default:
			case 'pending':
				$sender   = $this->guerrero->guerrero_id;
				$receiver = $friend_id;
				break;
			case 'accepted':
			case 'ignored':
				$sender   = $friend_id;
				$receiver = $this->guerrero->guerrero_id;
				break;
			}
			
			if (!$this->Guerrero_model->update_friends ($sender, $receiver, $status))
				throw new Exception;
			
			if ($status == 'accepted') {
				if ($friend = $this->Guerrero_model->guerreros ($friend_id))
					$this->_check_rank_up ($friend);
				$this->_check_rank_up ($this->guerrero);
				
				//La persona que te invita recibe un trofeo si es su primera invitacion o no habia recibido el premio. 
				//$this->Guerrero_model->update_trophies($friend_id, $this->guerrero->guerrero_rank_id, 'reclu');
			}		
			
			echo json_encode (array ('response'=>'success'));
		}
		
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}
	}
	
	
	//----
	
	
	/**
	 * Send Message
	 * -Proc method
	 */
	public function send_message()
	{
		try {
			$ref           = $this->input->post ('ref');
			$recipient     = $this->input->post ('recipient');
			$open_message  = $this->input->post ('open_message');
			$stock_message = $this->input->post ('predefined_message');
			
			$message       = empty ($open_message) ?
				$stock_message :
				$open_message;
			
			if (empty ($message))
				throw new Exception;
			if (!$this->Guerrero_model->write_messages ($message, $this->guerrero->guerrero_id, $recipient))
				throw new Exception ('Hubo un error enviando tu mensaje. Intenta nuevamente.');
			
			//Recompensa PENSADOR	// Amigo
			if(!$recipient)
			{
			 	$this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'pens');
			}
			else
			{
				$this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'amigo');
			}
			//Recompensa Super Guerrero
			$this->Guerrero_model->update_trophies($this->guerrero->guerrero_id, $this->guerrero->guerrero_rank_id, 'super');

		}
		
		catch (Exception $e) {
			$this->session->set_flashdata ('msg_error', $e->getMessage());
		}
		
		redirect ('social/' . $ref);
	}
	
	//----
	
	/**
	 * AJAX More Messages
	 */
	public function ajax_more_messages ($guerrero_id = 0)
	{
		header ('Content-type: application/json');
		
		try {
			$ref               = $this->input->post ('ref');
			$last_checked_date = $this->input->post ('last_checked_date');
			
			switch ($ref) {
			case 'index':
			default:
				$message_type = 'feed';
				break;
			case 'perfil':
				$message_type = 'timeline';
				break;
			}
			
			try{
				if (empty ($guerrero_id)
					OR !is_numeric ($guerrero_id)
					OR !$guerrero = $this->Guerrero_model->guerreros ($guerrero_id))
						throw new Exception;
			}
	    	
	    	catch (Exception $e) {
	    		// Defaults to current user's profile
	    		$guerrero = $this->guerrero;
	    	}
			
			if (!$messages = $this->Guerrero_model->messages_by_guerrero ($guerrero->guerrero_id, $message_type, $last_checked_date))
				throw new Exception;
			
			echo json_encode (array ('response'=>'success', 'messages'=>$messages));
		}
		catch (Exception $e) {
			echo json_encode (array ('response'=>'error'));
		}
	}
	
	
	
	// !Private Methods
	//------------------------------------------
	
	/**
	 * Check Rank Up
	 */
	private function _check_rank_up ($guerrero)
	{
		$this->load->library ('email');
		
		//actualizar trofeo de reclutador 
		$this->Guerrero_model->update_trophies($guerrero->guerrero_id, $guerrero->rank_id, 'reclu');
		
		try {
			$next_rank = $this->Guerrero_model->guerrero_next_rank ($guerrero->rank_id);
			
			if ($next_rank === FALSE
				OR ($guerrero->friend_total + 1) < $next_rank->rank_requirement)
					throw new Exception;
			
			//echo 'You are in command now, Admiral Piett.';
			
			// We just got promoted so let's update the db and send email notification
			
			if (!$this->Guerrero_model->write_ranks ($guerrero->guerrero_id, $next_rank->rank_id, array ('add_ticker', 'update_user')))
				throw new Exception;
			
			$this->email->initialize (array ('mailtype'=>'text'));
			$this->email->subject    ('Haz subido de rango - Guerreros de Luz');
			$this->email->message    ($this->load->view ('social/email_rank_view', array ('rank'=>$next_rank), TRUE));
			$this->email->from       ('auto@guerrerosdeluz.org', lang('app_client'));
			$this->email->to         ('"'. $guerrero->guerrero_real_name .'" <'. $guerrero->guerrero_email .'>');
			$result = $this->email->send();
			
			//Actualizar trofeos segun el rango 
			$this->Guerrero_model->update_trophies($guerrero->guerrero_id, $next_rank->rank_id, 'gue');
			
			return TRUE;
		}
		
		catch (Exception $e) {
			return FALSE;
		}
	}
	
	
	//----
	
	
	private function _rank_lightbox() {
		$new_ranks  = $this->Guerrero_model->ranks_by_guerrero ($this->guerrero->guerrero_id, 'new');
		$first_rank = current ($new_ranks);
		
		$this->Guerrero_model->write_ranks ($this->guerrero->guerrero_id, 0, array ('update_status'));
		
		return $new_ranks;
	}
}

/* End of file social.php */
/* Location: ./application/controllers/social.php */