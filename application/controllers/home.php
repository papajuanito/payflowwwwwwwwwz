<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
	// !Class Variables
	//------------------------------------------
	
	var $body_id  = 'home';
	var $guerrero = NULL;
	
	

	// !Public Methods
	//------------------------------------------
	
	/**
	 * Contructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		//$this->output->enable_profiler (TRUE);
		
		$this->load->model ('Guerrero_model');
		
		$guerrero_id    = $this->session->userdata ('so_guerrero_id');
		$this->guerrero = empty ($guerrero_id) ? $this->guerrero : $this->Guerrero_model->guerreros ($guerrero_id);
	}
	
	//----
	
	/**
	 * Index Page for this controller.
	 */
	public function index()
	{
		if (!empty ($this->guerrero))
			redirect ('social');
		
		$this->load->model ('Guerrero_model');
		$this->load->helper ('form');

		$data['page_title']    = lang ('app_title') .' // '. lang ('app_client');
		$data['main_content']  = 'homepage';
		$data['show_map']      = true;
		$data['email_success'] = $this->session->flashdata ('cu_email_success');
		
		$guerreros_count_by_place = $this->Guerrero_model->get_most_popular_places(4);
		
		$data['count_guerreros'] = $guerreros_count_by_place;
		
		$this->load->view('template', $data);
	}

	//----
	
	public function light_map()
	{
		$data['page_title'] 	= lang ('app_map_title');
		$data['main_content']	= 'light_map';
		$data['show_map'] 		= true;
		
		$guerreros_count_by_place = $this->Guerrero_model->get_most_popular_places(4);		
		$data['count_guerreros'] = $guerreros_count_by_place;

		
		$this->load->view('template', $data);
	}
	
	//----
	
	/**
	 * Get Waypoint ajax method
	 */

	public function get_guerreros_waypoints()
	{
		$this->load->model('Guerrero_model');
		
		header ('Content-type: application/json');
				
		try {
						
			$guerreros = $this->Guerrero_model->guerreros ('',true);
			
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
	
	/**
	 * Get the number of users for the Flash Light
	 */
	public function users()
	{
		$this->load->model('Guerrero_model');
		
		$data['users'] = 0;
		$breakdown = $this->Guerrero_model->guerreros_breakdown();
		
		foreach ($breakdown as $subscription_type) {
			$data['users'] += $subscription_type->subscription_type_total;
		}
		
		$this->load->view('users_data_view', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
