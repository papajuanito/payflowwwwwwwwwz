<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cuenta extends CI_Controller
{
	// !Class Variables
	//------------------------------------------
	
	var $body_id     = 'cuenta';
	var $guerrero    = NULL;
	var $email_error = FALSE;
	
	
	
	// !Public Methods
	//------------------------------------------
	
	/**
	 * Contructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		//$this->output->enable_profiler (TRUE);
		
		$this->lang->load ('cuenta');
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
		redirect('home');
	}
	
	//----
	
	public function registro ($step = 1)
	{
		$this->load->model ('Guerrero_model');
		$this->load->helper ('form');
		
		try {
			$registration				= $this->_registration_session();
			$view_data['registration']	= $registration;
			$view_data['validation']	= $this->session->flashdata ('cu_registro_validation');
			$view_data['step']			= !in_array ($step, array (1, 2, 3, 4)) ? 1 : $step;
			
			switch ($step) {
			case 1:
			default:
				$view_data['page_title']                 = lang('cu_registro_step1_title'); 
				$view_data['main_content']               = 'cuenta/registro_step1_view';
				$view_data['legion_list']                = $this->Guerrero_model->legions();
				$first_legion                            = current ($view_data['legion_list']);
				$view_data['selected_legion']            = empty ($registration->guerrero_legion_id) ?
					$first_legion->legion_id :
					$registration->guerrero_legion_id;
				break;
			case 2:
				if ($previous_step_errors = $this->_validate_registration ($registration, 1, 'all'))  // If missing previous step stuff, redirect there
					throw new Exception;
				
				$view_data['page_title']                 = lang('cu_registro_step2_title');
				$view_data['main_content']               = 'cuenta/registro_step2_view';
				$view_data['selected_country']           = $registration->guerrero_country;
				break;
			case 3:
				for ($i = 1; $i < 3; $i++)
					if ($previous_step_errors = $this->_validate_registration ($registration, $i, 'all'))  // If missing previous step stuff, redirect there
						throw new Exception;
				
				$view_data['page_title']                 = lang('cu_registro_step3_title');
				$view_data['main_content']               = 'cuenta/registro_step3_view';
				$view_data['subscription_type_list']     = $this->Guerrero_model->subscription_types();
				$first_subscription_type                 = current ($view_data['subscription_type_list']);
				$view_data['selected_subscription_type'] = empty ($registration->guerrero_subscription_type_id) ?
					$first_subscription_type->subscription_type_id :
					$registration->guerrero_subscription_type_id;
				$view_data['selected_state']             = $registration->cc_billing_state;
				$view_data['selected_country']           = $registration->cc_billing_country;
				$view_data['short_year']                 = date ('y');  // short year for dropdown
				$view_data['long_year']                  = date ('Y');  // long year for dropdown
				break;
			case 4:
				for ($i = 1; $i < 4; $i++)
					if ($previous_step_errors = $this->_validate_registration ($registration, $i, 'all'))  // If missing previous step stuff, redirect there
						throw new Exception;
				
				$view_data['page_title']                 = lang('cu_registro_step3_title');
				$view_data['main_content']               = 'cuenta/registro_step4_view';
				$view_data['subscription_type']          = $this->Guerrero_model->subscription_type_by_id ($registration->guerrero_subscription_type_id);
				break;
			}
			
			$this->load->view ('template', $view_data);
		}
		
		catch (Exception $e) {
			$this->session->set_flashdata ('cu_registro_validation', $previous_step_errors);
			redirect ('cuenta/registro/' . $i);
		}
	}
	
	//----
	
	public function registro_proc ($redirect_step = 1)
	{
		$this->load->helper ('email');
		$this->load->helper ('date');
		
		$step              = !in_array ($this->input->post ('step'), array (1, 2, 3, 4)) ? 1 : $this->input->post ('step');
		$registration      = $this->_registration_session ($step);
		$process_mode      = !$this->input->post ('submit_next_x') ? 'back_submit' : $step;
		$validation_errors = $this->_validate_registration ($registration, $process_mode);
		
		try {
			switch ($process_mode) {
			case 1:
			default:
				if ($validation_errors)
					throw new Exception;
				break;
			case 2:
				if ($validation_errors)
					throw new Exception;
				
				if ($previous_step_errors = $this->_validate_registration ($registration, 1, 'all')) {
					$step = 1;                                                         // If missing step 1 stuff, redirect there
					$validation_errors = $previous_step_errors;                        // Set error messages
					throw new Exception;
				}
				break;
			case 3:
				if ($validation_errors)  // Throw exception if something didn't validate to avoid trying payflow request
					throw new Exception();
				
				for ($i = 1; $i < 3; $i++) {
					if ($previous_step_errors = $this->_validate_registration ($registration, $i, 'all')) {
						$step = $i;                                                        // If missing previous step stuff, redirect there before attempting transaction
						$validation_errors = $previous_step_errors;                        // Set error messages
						throw new Exception;
					}
				}
				
				try {
					$this->_payflow_request ($registration);
					
					$registration->pnref = $this->payflow->response_arr['PNREF'];
					$this->session->set_userdata ('cu_registration', $registration);
				}
				
				catch (CardNumberException $e) {
					$validation_errors->cc_number          = $e->getMessage();
					throw $e;
				}
				catch (ExpDateException $e) {
					$validation_errors->cc_expiration_date = $e->getMessage();
					throw $e;
				}
				catch (CVV2Exception $e) {
					$validation_errors->cc_security        = $e->getMessage();
					throw $e;
				}
				catch (StreetAVSException $e) {
					$validation_errors->cc_billing_address = $e->getMessage();
					throw $e;
				}
				catch (ZipAVSException $e) {
					$validation_errors->cc_billing_zip     = $e->getMessage();
					throw $e;
				}
				catch (Exception $e) {
					$error_message = $e->getMessage();
					$validation_errors->cc_general         = empty ($error_message) ?
						lang ('cu_registro_err_payflow') :
						$error_message;
					throw $e;
				}
				break;
			case 4:
				try {
					for ($i = 1; $i < 4; $i++) {
						if ($previous_step_errors = $this->_validate_registration ($registration, $i, 'all')) {
							$step = $i;                                                        // If missing previous step stuff, redirect there before attempting transaction
							$validation_errors = $previous_step_errors;                        // Set error messages
							throw new Exception;
						}
					}
					
					$this->_payflow_request (
						$registration,
						$registration->guerrero_subscription_type_id == 1 ?
							'capture' :
							'recurring'
					);
				}
				
				catch (Exception $e) {
					$step			= 3;  // Go back to step 3 (payment) since there was a Payflow problem
					$error_message	= $e->getMessage();
					$validation_errors->cc_general = empty ($error_message) ?
						lang ('cu_registro_err_payflow') :
						$error_message;
					
					throw $e;
				}
				
				$registration->profile_id = $registration->guerrero_subscription_type_id == 1 ?
					$this->payflow->response_arr['PNREF'] :
					$this->payflow->response_arr['PROFILEID'];
				
				if (!$guerrero_id = $this->_add_guerrero ($registration)) {
					if ($this->email_error) {
						$step = 2;
						$validation_errors->guerrero_email = 'Ya hay una cuenta registrada con ese correo electrónico. Utiliza otro correo electrónico o <a href="'. site_url ('cuenta/login') .'">accede a la cuenta</a>.';
					}
					throw new Exception;
				}
				
				$this->session->set_userdata ('so_guerrero_id', $guerrero_id);
				$this->session->set_userdata ('cu_registration', NULL);
				$this->session->set_flashdata ('cu_email_success', '¡Felicidades! ¡Ya eres guerrero! Verifica tu correo electrónico.');
				
				redirect ('social');
				break;
			case 'back_submit':
				break;
			}
			
			$redirect_step = !in_array($redirect_step, array (1, 2, 3, 4)) ? 1 : $redirect_step;
			redirect ('cuenta/registro/' . $redirect_step);
		}
		
		catch (AVSException $e)
		{
			// If Payflow transaction was attempted
			if (isset($this->payflow->response_arr['PNREF']))
			{
				// VOID AUTHORIZATION
				$registration->pnref = $this->payflow->response_arr['PNREF'];
				
				try {
					$this->_payflow_request ($registration, 'void');
				}
				catch (Exception $e) {}  // This is just a precaution so we don't really care if it fails
			}
			
			$this->session->set_flashdata ('cu_registro_validation', $validation_errors);
		}
			
		catch (Exception $e)
		{			
			$this->session->set_flashdata ('cu_registro_validation', $validation_errors);
			redirect ('cuenta/registro/' . $step);
		}
	}
	
	
	//----
	
	
	public function login()
	{
		$this->load->helper ('form');
		
		$this->body_id = 'login';

		$view_data['page_title']   = 'Ingresar';
		$view_data['main_content'] = 'cuenta/cuenta_login_view';

		$this->load->view ('template.php', $view_data);
	}
	
	//----
	
	/**
	 * Authenticate User
	 * - Proc method (home/index)
	 */
	public function authenticate()
	{
		$this->load->model ('Guerrero_model');
		$this->load->helper ('email');
		$this->load->helper ('security');
		
		$redirects = array (
			'home'  => 'home',
			'login' => 'cuenta/login'
		);
		
		try {
			$username = $this->input->post ('username');
			$password = $this->input->post ('password');
			$source   = $this->input->post ('login_source');
			
			if (!in_array ($source, array_keys ($redirects)))
				throw new Exception;
			
			if (empty ($username)    OR !valid_email ($username)
				OR empty ($password) OR !is_string ($password))
					throw new Exception ('Tu email o contraseña no es válido(a).');
			
			$guerrero = $this->Guerrero_model->guerrero_by_email ($username);
			
			if ($guerrero === FALSE)
				throw new Exception (lang ('all_error_message'));
			if ($guerrero === NULL
				OR !check_password ($password, $guerrero->guerrero_password))
					throw new Exception ('Correo electrónico o contraseña incorrecta.');
			
			$this->Guerrero_model->update_guerrero (array ('guerrero_last_login'=>''), $guerrero->guerrero_id); // update last login
			
			$this->session->set_userdata ('so_guerrero_id', $guerrero->guerrero_id);
			redirect ('social');
		}
		
		catch (Exception $e) {
			$message = $e->getMessage();
			
			if (empty ($message))
				redirect ('home');
			
			$this->session->set_flashdata ('auth_error', $message);
			redirect ($redirects[$source]);
		}
	}

	//----
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect ('home');
	}
	
	
	//----
	
	
	/**
	 * Password Recovery Form
	 */
	public function recover_password()
	{
		$this->load->helper ('form');
		
		$this->body_id = 'recover';

		$view_data['page_title']    = 'Recuperación de contraseña';
		$view_data['main_content']  = 'cuenta/lost_password_view';
		$view_data['proc_response'] = $this->session->flashdata ('cu_recovery_error') ?
			$this->session->flashdata ('cu_recovery_error') :
			(object) array (
				'error' => '',
				'email' => ''
			);
		
		$this->load->view ('template.php', $view_data);
	}
	
	//----
	
	/**
	 * Password Recovery Form Processor
	 * - Proc method
	 */
	public function recover_password_proc()
	{
		$this->load->model ('Guerrero_model');
		
		try {
			$this->load->helper ('email');
			$this->load->helper ('string');
			
			$recovery_mail = $this->input->post ('recovery_email');
			
			if (empty ($recovery_mail) OR !valid_email ($recovery_mail))
				throw new Exception ('El correo electrónico proveído no es válido.');
			
			if ($guerrero = $this->Guerrero_model->guerrero_by_email ($recovery_mail))
			{
				$salt           = random_string ('sha1');
				$recovery_token = hash ('whirlpool', $guerrero->guerrero_id . $guerrero->guerrero_email . $salt);
				
				$this->Guerrero_model->update_guerrero (array ('recovery'=>$recovery_token), $guerrero->guerrero_id);
				$this->_send_guerrero_email ($guerrero->guerrero_id, 'recovery', $recovery_token);
				
			}
			
			$this->session->set_flashdata ('cu_recovery_error', NULL);
			$this->session->set_flashdata ('cu_recovery_success', 'Si la dirección de correo electrónico proveída está asociada a una cuenta de Guerreros de Luz se le enviará un correo con instrucciones para recuperar el password.');
		}
		
		catch (Exception $e) {
			$this->session->set_flashdata ('cu_recovery_error', (object) array (
				'error' => $e->getMessage(),
				'email' => $recovery_mail
			));
		}
		
		redirect ('cuenta/recover_password');
	}
	
	//----
	
	/**
	 * Password Reset Form
	 */
	public function reset_password ($recovery_token = '')
	{
		try {
			$this->load->model ('Guerrero_model');
			$this->load->helper ('form');
			
			if (empty ($recovery_token)
				OR strlen ($recovery_token) != 128
				OR !preg_match ('/[0-9a-f]{128,128}/', $recovery_token)
				OR !$guerrero = $this->Guerrero_model->guerrero_by_token ($recovery_token))
					throw new Exception;
			
			$this->body_id = 'recover';
	
			$view_data['page_title']     = 'Cambio de contraseña';
			$view_data['main_content']   = 'cuenta/reset_password_view';
			$view_data['recovery_token'] = $recovery_token;
			$view_data['proc_response']  = $this->session->flashdata ('cu_reset_error') ?
				$this->session->flashdata ('cu_reset_error') :
				(object) array ('email'=> '');
			
			$this->load->view ('template.php', $view_data);
		}
		
		catch (Exception $e) {
			redirect ('home');
		}
	}
	
	//----
	
	/**
	 * Password Reset Form
	 */
	public function reset_password_proc ($recovery_token = '')
	{
		try {
			$this->load->model ('Guerrero_model');
			$this->load->helper ('email');
			$this->load->helper ('security');
			
			$email        = $this->input->post ('reset_email');
			$password     = $this->input->post ('new_password');
			$confirmation = $this->input->post ('confirmation_password');
			$validation   = (object) array();
			
			if (empty ($recovery_token)
				OR strlen ($recovery_token) != 128
				OR !preg_match ('/[0-9a-f]{128,128}/', $recovery_token)
				OR !$guerrero = $this->Guerrero_model->guerrero_by_token ($recovery_token))
					throw new Exception;
			
			if (empty ($email)
				OR !valid_email ($email)
				OR $email != $guerrero->guerrero_email)
					$validation->email_error = 'El correo electrónico proveído no es válido.';
			
			if (empty ($password)        OR strlen ($password) < 8
				OR empty ($confirmation) OR strlen ($confirmation) < 8)
					$validation->password_error = 'La contraseña debe tener al menos 8 caracteres.';
			if ($password != $confirmation)
				$validation->password_error = 'Las contraseñas no son iguales.';
			
			if (count ((array) $validation))
				throw new Exception;
			
			$new_password = array (
				'guerrero_password' => encrypt_password ($password),
				'recovery'          => NULL
			);
			
			if (!$this->Guerrero_model->update_guerrero ($new_password, $guerrero->guerrero_id)) {
				$validation->general_error = 'Hubo un error cambiando el password. Intenta nuevamente.';
				throw new Exception;
			}
			
			$this->session->set_flashdata ('cu_recovery_success', 'Tu contraseña fue actualizada exitosamente.');
			redirect ('cuenta/recover_password');
		}
		
		catch (Exception $e)
		{
			if (empty ($validation))
				redirect ('home');
			else {
				$validation->email = $email;
				$this->session->set_flashdata ('cu_reset_error', $validation);
				redirect ('cuenta/reset_password/'.$recovery_token);
			}
		}
	}
	
	
	
	// !Private Methods
	//------------------------------------------
	
	/**
	 * Request Payment From Payflow
	 */
	private function _registration_session ($step = '')
	{
		//$this->session->set_userdata ('cu_registration', NULL);
		
		$registration = !$this->session->userdata ('cu_registration') ?
			(object) array (
				'guerrero_name'                 => '',
				'guerrero_legion_id'            => 0,
				'guerrero_real_name_first'      => '',
				'guerrero_real_name_last'       => '',
				'guerrero_is_name_private'      => FALSE,
				'guerrero_email'                => '',
				'guerrero_address_line1'        => '',
				'guerrero_address_line2'        => '',
				'guerrero_town'                 => '',
				'guerrero_country'              => 'PR',
				'guerrero_zip'                  => '',
				'guerrero_phone'                => '',
				'guerrero_birthday_picker'      => '',
				'guerrero_birthday'             => '',
				'guerrero_gender'               => '',
				'guerrero_map_town'             => '',
				'guerrero_geo_lat'              => '',
				'guerrero_geo_long'             => '',
				'guerrero_is_loc_private'       => FALSE,
				'guerrero_subscription_type_id' => 0,
				'cc_number'                     => '',
				'cc_expiration_month'           => '01',
				'cc_expiration_year'            => date ('y'),
				'cc_security'                   => '',
				'cc_billing_name_first'         => '',
				'cc_billing_name_last'          => '',
				'cc_billing_address1'           => '',
				'cc_billing_address2'           => '',
				'cc_billing_city'               => '',
				'cc_billing_state'              => 'PR',
				'cc_billing_zip'                => '',
				'cc_billing_country'            => 'PR',
				'cc_billing_approval'           => FALSE,
				'pnref'                         => ''
			) :
			$this->session->userdata ('cu_registration');
		
		switch ($step) {
		case 1:
			$registration->guerrero_name                 = !$this->input->post ('guerrero_name') ? $this->input->post ('guerrero_name_preset') : $this->input->post ('guerrero_name');
			$registration->guerrero_legion_id            = $this->input->post ('guerrero_legion_id');
			break;
		case 2:
			$registration->guerrero_real_name_first      = $this->input->post ('guerrero_real_name_first');
			$registration->guerrero_real_name_last       = $this->input->post ('guerrero_real_name_last');
			$registration->guerrero_is_name_private      = (bool) $this->input->post ('guerrero_is_name_private');
			$registration->guerrero_email                = $this->input->post ('guerrero_email');
			$registration->guerrero_address_line1        = $this->input->post ('guerrero_address_line1');
			$registration->guerrero_address_line2        = $this->input->post ('guerrero_address_line2');
			$registration->guerrero_town                 = $this->input->post ('guerrero_town');
			$registration->guerrero_country              = $this->input->post ('country');
			$registration->guerrero_zip                  = $this->input->post ('guerrero_zip');
			$registration->guerrero_is_loc_private       = (bool) $this->input->post ('guerrero_is_loc_private');
			$registration->guerrero_phone                = $this->input->post ('guerrero_phone');
			$registration->guerrero_birthday_picker      = $this->input->post ('guerrero_birthday_picker');
			$registration->guerrero_birthday             = $this->input->post ('guerrero_birthday');
			$registration->guerrero_gender               = $this->input->post ('guerrero_gender');
			$registration->guerrero_map_town             = $this->input->post ('guerrero_map_town');
			$registration->guerrero_geo_lat              = $this->input->post ('guerrero_geo_lat');
			$registration->guerrero_geo_long             = $this->input->post ('guerrero_geo_long');
			
			if (empty ($registration->cc_billing_name_first) && empty ($registration->cc_billing_address1))
			{
				$registration->cc_billing_name_first     = $registration->guerrero_real_name_first;
				$registration->cc_billing_name_last      = $registration->guerrero_real_name_last;
				$registration->cc_billing_address1       = $registration->guerrero_address_line1;
				$registration->cc_billing_address2       = $registration->guerrero_address_line2;
				$registration->cc_billing_city           = $registration->guerrero_town;
				$registration->cc_billing_state          = '';
				$registration->cc_billing_zip            = $registration->guerrero_zip;
				$registration->cc_billing_country        = $registration->guerrero_country;
			}
			break;
		case 3:
			$registration->guerrero_subscription_type_id = $this->input->post ('guerrero_subscription_type_id');
			$registration->cc_number                     = $this->input->post ('cc_number');
			$registration->cc_expiration_month           = $this->input->post ('cc_expiration_month');
			$registration->cc_expiration_year            = $this->input->post ('cc_expiration_year');
			$registration->cc_security                   = $this->input->post ('cc_security');
			$registration->cc_billing_name_first         = $this->input->post ('cc_billing_name_first');
			$registration->cc_billing_name_last          = $this->input->post ('cc_billing_name_last');
			$registration->cc_billing_address1           = $this->input->post ('cc_billing_address1');
			$registration->cc_billing_address2           = $this->input->post ('cc_billing_address2');
			$registration->cc_billing_city               = $this->input->post ('cc_billing_city');
			$registration->cc_billing_state              = $this->input->post ('state');
			$registration->cc_billing_zip                = $this->input->post ('cc_billing_zip');
			$registration->cc_billing_country            = $this->input->post ('country');
			break;
		case 4:
			$registration->cc_billing_approval           = (bool) $this->input->post ('cc_billing_approval');
			break;
		default:
			break;
		}
		
		$this->session->set_userdata ('cu_registration', $registration);
		
		return $registration;
	}
	
	//----
	
	/**
	 * Validate Registration
	 */
	private function _validate_registration ($registration, $step = 1, $type = 'form_only')
	{
		$this->load->helper ('email');
		$this->load->helper ('date');
		
		$validation = (object) array();
		
		try {
			switch ($step) {
			case 1:
				if (empty ($registration->guerrero_name)      OR !is_string ($registration->guerrero_name))
					$validation->guerrero_name      = 'Escribe tu nombre de guerrero antes de continuar.';
				if (empty ($registration->guerrero_legion_id) OR !is_numeric ($registration->guerrero_legion_id))
					$validation->guerrero_legion_id = 'Selecciona tu legión antes de continuar.';
				break;
			case 2:
				if (empty ($registration->guerrero_real_name_first) OR !is_string ($registration->guerrero_real_name_first))
					$validation->guerrero_real_name_first = 'Escribe tu nombre antes de continuar.';
				if (empty ($registration->guerrero_real_name_last)  OR !is_string ($registration->guerrero_real_name_last))
					$validation->guerrero_real_name_last  = 'Escribe tu apellido antes de continuar.';
				if (empty ($registration->guerrero_email)           OR !is_string ($registration->guerrero_email))
					$validation->guerrero_email           = 'Escribe tu correo electrónico antes de continuar.';
				else if (!valid_email ($registration->guerrero_email))
					$validation->guerrero_email           = 'Este correo electrónico no es válido.';
				else if ($this->Guerrero_model->guerrero_by_email ($registration->guerrero_email))
					$validation->guerrero_email           = 'Ya hay una cuenta registrada con ese correo electrónico. Utiliza otro correo electrónico o <a href="'. site_url ('cuenta/login') .'">accede a la cuenta</a>.';
				if (empty ($registration->guerrero_address_line1)   OR !is_string ($registration->guerrero_address_line1))
					$validation->guerrero_address_line1   = 'Escribe tu dirección antes de continuar.';
				if (empty ($registration->guerrero_town)            OR !is_string ($registration->guerrero_town))
					$validation->guerrero_town            = 'Escribe tu pueblo antes de continuar.';
				if (empty ($registration->guerrero_zip)             OR !is_string ($registration->guerrero_zip))
					$validation->guerrero_zip             = 'Escribe tu código postal antes de continuar.';
				if (empty ($registration->guerrero_country)         OR !is_string ($registration->guerrero_country))
					$validation->guerrero_country         = 'Selecciona tu país antes de continuar.';
				if (empty ($registration->guerrero_phone)           OR !is_string ($registration->guerrero_phone))
					$validation->guerrero_phone           = 'Escribe tu número de teléfono antes de continuar.';
				if (empty ($registration->guerrero_birthday)
					OR !is_string ($registration->guerrero_birthday)
					OR empty ($registration->guerrero_birthday_picker))
						$validation->guerrero_birthday    = 'Selecciona o escribe tu fecha de nacimiento antes de continuar.';
				else if (!valid_date ($registration->guerrero_birthday))
					$validation->guerrero_birthday        = 'Tu fecha de nacimiento no es válida. Selecciona tu fecha de nacimiento.';
				if (empty ($registration->guerrero_gender)          OR !in_array ($registration->guerrero_gender, array ('M', 'F')))
					$validation->guerrero_gender          = 'Selecciona tu género antes de continuar.';
				if (empty ($registration->guerrero_map_town)        OR !is_string ($registration->guerrero_map_town)
					OR empty ($registration->guerrero_geo_lat)      OR !is_numeric ($registration->guerrero_geo_lat)
					OR empty ($registration->guerrero_geo_long)     OR !is_numeric ($registration->guerrero_geo_long)
					OR $registration->guerrero_geo_lat  < -90       OR $registration->guerrero_geo_lat  > 90
					OR $registration->guerrero_geo_long < -180      OR $registration->guerrero_geo_long > 180)
						$validation->guerrero_map_town    = 'Selecciona tu ubicación antes de continuar.';
				//if (!is_bool ($registration->guerrero_is_name_private)	OR !is_bool ($registration->guerrero_is_loc_private))
				break;
			case 3:
				if (empty ($registration->guerrero_subscription_type_id) OR !is_numeric ($registration->guerrero_subscription_type_id))
					$validation->guerrero_subscription_type_id = 'Selecciona tu donación antes de continuar.';
				if (empty ($registration->cc_number)                     OR !is_numeric ($registration->cc_number))
					$validation->cc_number                     = 'Escribe tu número de tarjeta de crédito antes de continuar.';
				if (empty ($registration->cc_expiration_month)           OR !is_numeric ($registration->cc_expiration_month)
					OR empty ($registration->cc_expiration_year)         OR !is_numeric ($registration->cc_expiration_year))
						$validation->cc_expiration_date        = 'Selecciona la fecha de vencimiento de tu tarjeta antes de continuar.';
				if (!empty ($registration->cc_security)                  && !is_numeric ($registration->cc_security))
					$validation->cc_security                   = 'Escribe el código de seguridad de tu tarjeta antes de continuar.';
				if (empty ($registration->cc_billing_name_first)         OR !is_string ($registration->cc_billing_name_first))
					$validation->cc_billing_name_first         = 'Escribe tu nombre de facturación antes de continuar.';
				if (empty ($registration->cc_billing_name_last)          OR !is_string ($registration->cc_billing_name_last))
					$validation->cc_billing_name_last          = 'Escribe tu apellido de facturación antes de continuar.';
				if (empty ($registration->cc_billing_address1)           OR !is_string ($registration->cc_billing_address1))
					$validation->cc_billing_address            = 'Escribe tu dirección antes de continuar.';
				if (empty ($registration->cc_billing_city)               OR !is_string ($registration->cc_billing_city))
					$validation->cc_billing_city               = 'Escribe tu pueblo antes de continuar.';
				
				if (in_array ($registration->cc_billing_country, array ('CA', 'US', 'UM'))) {
					if (empty ($registration->cc_billing_state)          OR !is_string ($registration->cc_billing_state))
						$validation->cc_billing_state          = 'Selecciona tu estado antes de continuar.';
				}
				
				if (empty ($registration->cc_billing_zip)                OR !is_string ($registration->cc_billing_zip))
					$validation->cc_billing_zip                = 'Escribe tu código postal antes de continuar.';
				if (empty ($registration->cc_billing_country)            OR !is_string ($registration->cc_billing_country))
					$validation->cc_billing_country            = 'Selecciona tu país antes de continuar.';
				
				if ($type == 'all')
					if (empty ($registration->pnref)                     OR !is_string ($registration->pnref))
						$validation->pnref                     = '';
				
				break;
			default:
				break;
			}
			
			if (count ((array) $validation))
				throw new Exception();
			
			return FALSE;
		}
		
		catch (Exception $e) {
			return $validation;
		}
	}
	
	//----
	
	/**
	 * Request Authorization, Void or Recurring Payment From Payflow
	 */
	private function _payflow_request ($registration, $transaction_type = 'authorization')
	{
		$this->load->library ('Payflow');
		$this->load->model ('Guerrero_model');
		
		$insert_auth = FALSE;
		$delete_auth = TRUE;
		
		switch ($transaction_type) {
		case 'authorization':
			$subscription_type = $this->Guerrero_model->subscription_type_by_id ($registration->guerrero_subscription_type_id);
			
			$this->payflow->TRXTYPE  = 'A';	// A = Authorization transaction type
			$this->payflow->AMT      = $subscription_type->subscription_type_fee;   // Amount in dollars
			$this->payflow->COMMENT1 = 'Autorizacion de Guerreros de Luz para usuario: ' . $registration->guerrero_email;
			// ORDERDESC
			
			$insert_auth = TRUE;
			$delete_auth = FALSE;
			break;
		case 'void':
			$this->payflow->TRXTYPE = 'V';	// V = Void transaction type
			$this->payflow->ORIGID	= $registration->pnref;	// Authorization PNREF
			break;
		case 'capture':
			$subscription_type = $this->Guerrero_model->subscription_type_by_id ($registration->guerrero_subscription_type_id);
			
			$this->payflow->TRXTYPE     = 'D';  // D = Delayed capture transaction type
			$this->payflow->ORIGID		= $registration->pnref;									// Authorization PNREF
			$this->payflow->AMT			= $subscription_type->subscription_type_fee;			// Amount in dollars (should be $2)
			$this->payflow->PROFILENAME = 'Guerrero de Luz: ' . $registration->guerrero_email;	// Display name for payment profile
			$this->payflow->COMMENT1    = 'Subscripcion a Guerreros de Luz para usuario: ' . $registration->guerrero_email;
			break;
		case 'recurring':
			$subscription_type = $this->Guerrero_model->subscription_type_by_id ($registration->guerrero_subscription_type_id);
			
			$this->payflow->TRXTYPE			= 'R';		// R = Recurring transaction type
			$this->payflow->ACTION			= 'A';		// A = Add new recurring payment
			$this->payflow->PAYPERIOD		= 'MONT';	// MONT = Monthly payment
			$this->payflow->TERM			= 0;		// No predetermined amount of payments (keep going until stopped)
			$this->payflow->RETRYNUMDAYS	= 20;		// Retry payment for 20 days if failure
			$this->payflow->MAXFAILPAYMENTS	= 1;		// Deactivate recurring payment after missing 1 month
			$this->payflow->START			= date ('mdY', strtotime ('tomorrow'));					// Start date (tomorrow)
			$this->payflow->ORIGID			= $registration->pnref;									// Authorization PNREF
			$this->payflow->AMT				= $subscription_type->subscription_type_fee;			// Amount in dollars
			$this->payflow->PROFILENAME		= 'Guerrero de Luz: ' . $registration->guerrero_email;	// Display name for payment profile
			break;
		}
		
		// Account info
		$this->payflow->PARTNER   = 'verisign';
		$this->payflow->VENDOR    = 'rmfoundation';
		$this->payflow->USER      = 'guerreros';
		$this->payflow->PWD       = '00guerreroscash00';
		
		// Transaction info
		$this->payflow->TENDER    = 'C';                                    // C = Credit card transaction
		$this->payflow->ACCT      = $registration->cc_number;               // Credit card number
		$this->payflow->EXPDATE   = $registration->cc_expiration_month . $registration->cc_expiration_year;	// Credit card expiration date (format: mmyy)
		$this->payflow->CVV2      = $registration->cc_security;             // Credit card code (behind card)
		
		// Customer info
		$this->payflow->FIRSTNAME = $registration->cc_billing_name_first;
		$this->payflow->LASTNAME  = $registration->cc_billing_name_last;
		
		$this->payflow->STREET    = $registration->cc_billing_address1;
		$this->payflow->CITY      = $registration->cc_billing_city;
		$this->payflow->STATE     = $registration->cc_billing_state;
		$this->payflow->ZIP       = $registration->cc_billing_zip;
		$this->payflow->COUNTRY   = $registration->cc_billing_country;
		$this->payflow->EMAIL     = $registration->guerrero_email;
		$this->payflow->CUSTIP    = $this->input->ip_address();
		
		try {
			$this->payflow->process();
			
			if ($insert_auth) {
				$this->Guerrero_model->write_authorizations ($this->payflow->response_arr['PNREF']);
				
				if (!empty ($registration->pnref)) {
					try {
						$this->_payflow_request ($registration, 'void');
					}
					catch (Exception $e) {}
				}
			}
			
			if ($delete_auth)
				$this->Guerrero_model->delete_authorizations ($this->payflow->ORIGID);
		}
		
		catch (StreetAVSException $e) {
			try {
				if (empty ($registration->cc_billing_address2))
					throw $e;
				
				try {	
					$this->_payflow_request ($registration, 'void');
				} catch (Exception $e) {}
				$this->payflow->STREET .= ' '. $registration->cc_billing_address2;
				$this->payflow->process();
			}
			catch (StreetAVSException $e) {
				try {
					if (empty ($registration->cc_billing_address2))
						throw $e;
					
					try {	
						$this->_payflow_request ($registration, 'void');
					} catch (Exception $e) {}
					$this->payflow->STREET   = $registration->cc_billing_address2;
					$this->payflow->COMMENT2 = $registration->cc_billing_address1;
					
					$this->payflow->process();
				}
				catch (StreetAVSException $e) {
					try {
						// Let's fuck around with the PayPal Billing Address shit		
						$t_a   = $registration->cc_billing_address1 .' '. $registration->cc_billing_address2;
						$e_t_a = explode(' ', $t_a); $t_n_a = array();
						foreach($e_t_a as $k=>$v)
							if (is_numeric ($v)) {
								$numbers_numbers = $v;
								break;
							}
						
						if (!isset ($numbers_numbers))
							throw $e;
						
						$this->payflow->COMMENT2 = $t_a;
						$this->payflow->STREET   = $numbers_numbers;
						$this->payflow->process();
					}
					
					catch (StreetAVSException $e) {
						try {	
							$this->_payflow_request ($registration, 'void');
						} catch (Exception $e) {}
						$this->payflow->avs_addr_required = FALSE;
						$this->payflow->STREET = $registration->cc_billing_address1 .' '. $registration->cc_billing_address2;
						$this->payflow->process();
					}
				}
			}
		}
	}
	
	//----
	
	/**
	 * Add Guerrero
	 */
	private function _add_guerrero ($registration)
	{
		try {
			$this->load->model ('Guerrero_model');
			$this->load->helper ('string');
			$this->load->helper ('security');
			
			if (empty ($registration->profile_id) OR !is_string ($registration->profile_id))
				throw new Exception;
			
			if ($this->Guerrero_model->guerrero_by_email ($registration->guerrero_email)) {
				$this->email_error = TRUE;
				throw new Exception;
			}
			
			$password = random_string ('alnum', 16);
			
			$new_guerrero = array (
				'guerrero_email'                => $registration->guerrero_email,
				'guerrero_password'             => encrypt_password ($password),
				'guerrero_name'                 => $registration->guerrero_name,
				'guerrero_real_name'            => $registration->guerrero_real_name_first .' '. $registration->guerrero_real_name_last,
				'guerrero_is_name_private'      => $registration->guerrero_is_name_private,
				'guerrero_map_town'             => $registration->guerrero_map_town,
				'guerrero_map_country'          => trim (end (explode (',', $registration->guerrero_map_town))),
				'guerrero_geo_lat'              => $registration->guerrero_geo_lat,
				'guerrero_geo_long'             => $registration->guerrero_geo_long,
				'guerrero_is_loc_private'       => $registration->guerrero_is_loc_private,
				'guerrero_subscription_type_id' => $registration->guerrero_subscription_type_id,
				'guerrero_legion_id'            => $registration->guerrero_legion_id,
				'guerrero_address_line1'        => $registration->guerrero_address_line1,
				'guerrero_address_line2'        => $registration->guerrero_address_line2,
				'guerrero_town'                 => $registration->guerrero_town,
				'guerrero_country'              => $registration->guerrero_country,
				'guerrero_zip'                  => $registration->guerrero_zip,
				'guerrero_phone'                => $registration->guerrero_phone,
				'guerrero_birthday'             => $registration->guerrero_birthday,
				'guerrero_gender'               => $registration->guerrero_gender,
				'guerrero_payment'              => $registration->profile_id,
				'guerrero_last_login'           => ''
			);
			
			if (!$guerrero_id = $this->Guerrero_model->update_guerrero ($new_guerrero)
				OR !$this->_send_guerrero_email ($guerrero_id, 'registration', $password))
					throw new Exception();
			
			return $guerrero_id;
		}
		
		catch (Exception $e) {
			return FALSE;
		}
	}
	
	//----
	
	private function _send_guerrero_email ($guerrero_id, $type, $extra)
	{
		try {
			$this->load->model ('Guerrero_model');
			$this->load->library ('email');
			
			if (empty ($guerrero_id) OR !is_numeric ($guerrero_id)
				OR empty ($extra)    OR !is_string ($extra)
				OR !$guerrero = $this->Guerrero_model->guerreros ($guerrero_id))
					throw new Exception;
			
			
			switch ($type) {
			case 'registration':
				$guerrero->password = $extra;
				
				$this->email->initialize (array ('mailtype'=>'text'));
				$this->email->subject ('¡Bienvenid@ a Guerreros de Luz!');
		        $this->email->message ($this->load->view ('cuenta/invite_email_view', array ('guerrero'=>$guerrero), TRUE));
				break;
			case 'recovery':
				$guerrero->recovery_token = $extra;
				
				$this->email->initialize (array ('mailtype'=>'html'));
				$this->email->subject ('Solicitud de Cambio de Contraseña - Guerreros de Luz');
		        $this->email->message ($this->load->view ('cuenta/recovery_email_view', array ('guerrero'=>$guerrero), TRUE));
				break;
			default:
				break;
			}
			
			$this->email->from ('auto@guerrerosdeluz.org', lang('app_client'));
			$this->email->to ('"'. $guerrero->guerrero_real_name .'" <'. $guerrero->guerrero_email .'>');
			return $this->email->send();
		}
		
		catch (Exception $e) {
			return FALSE;
		}
	}
}

/* End of file cuenta.php */
/* Location: ./application/controllers/cuenta.php */