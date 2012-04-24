<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Guerrero_model extends CI_Model
{
	// !Class Variables
	// ------------------------------------------
	
	var $legions_table            = 'legions';
	var $subscription_types_table = 'subscription_types';
	var $guerreros_table          = 'guerreros';
	var $guerreros_ranks_table	  = 'guerreros_ranks';
	var $friends_table            = 'friends';
	var $password_recover_table   = 'password_recover';
	var $ticker_table             = 'ticker_history_dev';
	var $messages_table           = 'messages';
	var $ranks_table              = 'ranks';
	var $auth_table               = 'authorizations';
	
	
	
	// !Public Methods
	// ------------------------------------------
	
	/**
	 * Legion By Id
	 */
	public function legion_by_id ($legion_id = 0)
	{		
		if (empty ($legion_id) OR !is_numeric ($legion_id))
			return FALSE;
		
		$this->db->where ('legion_id', $legion_id);
		$result = $this->db->get ($this->legions_table);
		
		if (!$result OR !$result->num_rows)
			return FALSE;
		
		return current ($result->result());
	}
	
	//----
	
	/**
	 * Legions
	 */
	public function legions()
	{
		$this->db->where ('legion_tag !=', 'rm');
		$result = $this->db->get ($this->legions_table);
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	

	//----
	
	
	/**
	 * Subscription Type By Id
	 */
	public function subscription_type_by_id ($subscription_type_id = 0)
	{		
		if (empty ($subscription_type_id) OR !is_numeric ($subscription_type_id))
			return FALSE;
		
		$this->db->where ('subscription_type_id', $subscription_type_id);
		$result = $this->db->get ($this->subscription_types_table);
		
		if (!$result OR !$result->num_rows)
			return FALSE;
		
		return current ($result->result());
	}
	
	//----
	
	/**
	 * Subscription Types
	 */
	public function subscription_types()
	{
		$this->db->order_by ('subscription_type_fee', 'ASC');
		$result = $this->db->get ($this->subscription_types_table);
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	
	/**
	 * Get Guerrero by Email (username)
	 */
	public function guerrero_by_email ($guerrero_email = '')
	{
		$this->load->helper ('email');
		
		if (empty ($guerrero_email) OR !valid_email ($guerrero_email))
			return FALSE;
		
		$this->_guerrero_query_setup();
		$this->db->where ('guerrero_email', $guerrero_email);
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		if (!$result->num_rows)
			return NULL;
		
		return current ($result->result());
	}
	
	//----
	
	/**
	 * Get Guerrero by Recovery Token (username)
	 */
	public function guerrero_by_token ($guerrero_token = '')
	{
		$this->load->helper ('date');
		
		if (empty ($guerrero_token) OR !is_string ($guerrero_token))
			return FALSE;
		
		$this->_guerrero_query_setup();
		$this->db->join ($this->password_recover_table, 'guerrero_id = recover_guerrero_id');
		$this->db->where ('recover_token', $guerrero_token);
		$this->db->where ('recover_expiration >', mnow());
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		if (!$result->num_rows)
			return NULL;
		
		return current ($result->result());
	}
	
	//----
	
	/**
	 * Get Top Guerreros
	 */
	public function guerreros_top ($limit)
	{
		$this->_guerrero_query_setup();
		$this->db->order_by ('friend_total', 'DESC');		
		$this->db->limit ($limit);
		$result = $this->db->get();

		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	//----
	
	/**
	 * Get Guerreros
	 */
	public function guerreros ($guerrero_id = '', $map='')
	{
		$return_single = FALSE;
		
		if (!empty($guerrero_id) && is_numeric ($guerrero_id)) {
			$return_single = TRUE;
			$this->db->where ('guerrero_id', $guerrero_id);
		}
		
		//!in the case this model function is called from the map we use a diferent query setup function
		if($map)
		{
			$this->db->where ('guerrero_is_loc_private', 0);
			$this->_guerrero_map_query_setup();
		}
		else
		{
			$this->_guerrero_query_setup();
		}
		
		
		$result = $this->db->get();

		if(!$result)
			return FALSE;
		
		$guerreros = $result->result();
		
		return $return_single ?
			current ($guerreros) :
			$guerreros;
	}
	
	//----
	
	/**
	 * Guerrero search
	 */
	public function guerrero_search ($guerrero_args, $page = 0, $limit = 10, $order_by='default')
	{
		if (!is_array ($guerrero_args) OR !is_numeric ($page))
			return FALSE;
		
		$or_where ='';
		if(isset($guerrero_args['guerrero_map_country']))
		{
			switch ($guerrero_args['guerrero_map_country'])
			{
				case 'Puerto Rico':
					$or_where = 'guerrero_map_country = "Commonwealth of Puerto Rico"';
				break;
				case 'Estados Unidos':
					$or_where = 'guerrero_map_country ="USA" OR guerrero_map_country ="United States" OR guerrero_map_country ="EEUU" ' ;
				break;
				case 'Brasil' :
					$or_where = 'guerrero_map_country ="Brazil"';
				break;
				case 'Reino Unido': 
					$or_where = 'guerrero_map_country = "UK" ' ;
				break;
				case 'República Dominicana' : 
					$or_where = 'guerrero_map_country = "Dominican Republic" ' ;
				break;
				case 'Japón' :
					$or_where = 'guerreros_map_country = "Japan"';
				break;
				case 'Italia' : 
					$or_where = 'guerreros_map_country = "Italy"';
				break;
			}
		}
		
		$this->db->from ($this->guerreros_table);
		$this->db->join ($this->legions_table, 'guerrero_legion_id = legion_id');
		$this->db->where ($guerrero_args);
		if($or_where !=''){
			$this->db->or_where($or_where);
		}
		$total = $this->db->count_all_results();
		
		$this->_guerrero_query_setup();
		$this->db->where ($guerrero_args);
		if($or_where !=''){
			$this->db->or_where($or_where);
		}
		$this->db->limit ($limit, $page);
		if($order_by != 'default')
		{	
			if($order_by == 'guerrero_last_login')
			{
				$this->db->order_by($order_by,'desc');
			}
			else
			{
				$this->db->order_by($order_by);			
			}
		}

		$result = $this->db->get();

		if (!$result)
			return FALSE;
		
		return (object) array (
			'total'     => $total,
			'guerreros' => $result->result()
		);
	}
	
	
	//----
	
		/**
	 * Guerrero search backend
	 */
	public function guerrero_search_backend ($guerrero_args, $page = 0, $limit = 10, $order_by='default')
	{
		if (!is_array ($guerrero_args) OR !is_numeric ($page))
			return FALSE;
		
		$this->db->from ($this->guerreros_table);
		$this->db->like ($guerrero_args);
		$total = $this->db->count_all_results();		
		$this->_guerrero_query_setup();
		$this->db->like ($guerrero_args);
		
		$this->db->limit ($limit, $page);
		if($order_by != 'default')
		{	
			if($order_by == 'guerrero_last_login')
			{
				$this->db->order_by($order_by,'desc');
			}
			else
			{
				$this->db->order_by($order_by);			
			}
		}

		$result = $this->db->get();

		if (!$result)
			return FALSE;
		
		return (object) array (
			'total'     => $total,
			'guerreros' => $result->result()
		);
	}
	
	
	//----
	
	/**
	 * Recommended Guerreros
	 */
	public function recommended_guerreros ($guerrero_id, $limit, $page = 0)
	{
		if (empty ($guerrero_id) OR !is_numeric ($guerrero_id)
			OR empty ($limit)    OR !is_numeric ($limit))
				return FALSE;
		
		$this->db->select   ('`'. $this->guerreros_table .'`.*,
			UNIX_TIMESTAMP(`guerrero_birthday`)   AS `guerrero_birthday_stamp`,
			UNIX_TIMESTAMP(`guerrero_created`)    AS `guerrero_created_stamp`,
			UNIX_TIMESTAMP(`guerrero_modified`)   AS `guerrero_modified_stamp`,
			UNIX_TIMESTAMP(`guerrero_last_login`) AS `guerrero_last_login_stamp`,
			`'. $this->legions_table .'`.*,
			`'. $this->ranks_table .'`.*', FALSE
		);
		$this->db->from         ($this->guerreros_table);
		$this->db->join         ($this->legions_table, 'guerrero_legion_id = legion_id');
		$this->db->join         ($this->ranks_table, 'guerrero_rank_id = rank_id');
		$this->_friend_subquery ($guerrero_id, 'pending', 'guerrero_id');
		$this->db->where_not_in ('guerrero_id', array (1, $guerrero_id));
		$this->db->order_by     ('RAND()');
		$this->db->limit        ($limit, $page);
		$result = $this->db->get();

		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	 /**
	 * Get the Guerrero next rank
	 */
	 public function guerrero_next_rank($guerrero_rank_id)
	 {
	 	try {
		 	$next_rank = (int)$guerrero_rank_id;
		 	$next_rank++;
		 	
		 	if ($next_rank > 9) {
		 		return NULL;
		 	}
		 	else {
		 		if (!$query = $this->db->get_where($this->ranks_table, array('rank_id' => $next_rank)))
		 			throw new Exception;
		 		
				return current ($query->result());
		 	}
	 	}
	 	
	 	catch (Exception $e) {
	 		return FALSE;
	 	}
	 }
	 
	 /**
	 * Get the Guerrero completed ranks
	 */
	 public function guerrero_completed_ranks($guerrero_rank_id)
	 {
	 	if ($guerrero_rank_id > 1) {
	 		$this->db->select('*');
	 		$this->db->from($this->ranks_table);
	 		$this->db->where('rank_id <', $guerrero_rank_id);
	 		$this->db->order_by('rank_id', 'DESC');
	 		$query = $this->db->get();
	 		$result = $query->result();
	 		
			return $result;
	 	} else {
	 		return FALSE;
	 	}
	 }
	 
	 /**
	 * Get the Guerrero current legion
	 */
	 public function guerrero_legion($guerrero_legion_id)
	 {
	 	$query = $this->db->get_where($this->legions_table, array('legion_id' => $guerrero_legion_id) );
		$result = $query->result();
		
		return $result[0];
	 }
	 
	 /**
	 * Total number of guerreros, excluding Ricky Martin
	 */
	 public function guerreros_breakdown()
	 {
	 	$this->db->select   ($this->subscription_types_table . '.*, COUNT(`guerrero_subscription_type_id`) AS `subscription_type_total`');
	 	$this->db->from     ($this->subscription_types_table);
	 	$this->db->join     ($this->guerreros_table, 'subscription_type_id = guerrero_subscription_type_id', 'LEFT');
	 	$this->db->where ('(`guerrero_id` IS NULL OR `guerrero_id` != 1)');
	 	$this->db->where ('guerrero_is_test', FALSE);
	 	$this->db->group_by ('subscription_type_id');
	 	$result = $this->db->get();
	 	
	 	if (!$result OR !$result->num_rows())
	 		return FALSE;
	 	
	 	return $result->result();
	 }
	
	//----
	
	public function guerreros_transaction()
	{
		$this->db->select($this->subscription_types_table . '.*, guerrero_id, `guerreros`.`guerrero_created`');
		$this->db->from($this->subscription_types_table);	
		$this->db->join($this->guerreros_table, 'subscription_type_id = guerrero_subscription_type_id', 'LEFT');
		$this->db->where ('guerrero_is_test', FALSE);
		$result = $this->db->get();
		
		if (!$result OR !$result->num_rows())
	 		return FALSE;
	 	
	 	return $result->result();
	}
	
	/**
	 * Guerreros count by legion
	 */
	public function guerreros_by_legions()
	{
		
		$this->db->limit(4);		
		$result = $this->db->get ($this->legions_table);
		
		$legions = $result->result();
		
		$legions_breakdown = array();
		
		foreach ($legions as $l )
		{
			$this->db->where ('guerrero_legion_id', $l->legion_id);
			$r = $this->db->get ($this->guerreros_table);
			$legions_breakdown[$l->legion_tag] = count($r->result());
		}
		if (!$result OR !$result->num_rows)
			return FALSE;
		
		return $legions_breakdown;		
	}
	//----
	
	/**
	 * Guerreros count by trophies
	 */
	public function guerreros_by_trophies()
	{

		$result = $this->db->get ('trophies');
		
		$trophies = $result->result();
		
		
		$trophies_breakdown = array();
		
		foreach ($trophies as $t )
		{	
			$this->db->where('gt_trophy_id', $t->trophy_id);
			$r = $this->db->get('guerreros_trophies');
			$trophies_breakdown[$t->trophy_style] = count($r->result());
		}
		if (!$result OR !$result->num_rows)
			return FALSE;
		
		return $trophies_breakdown;		
	}
	//----
	
	/**
	 * Messages Total
	 */
	public function count_all_messages()
	{
		$r = $this->db->get('messages');
		$total_msg = count($r->result());
		return $total_msg;
	}
	//----
	
	
	/**
	 * Friend Status
	 */
	public function friend_status ($guerrero_id, $friend_id)
	{
		if ($guerrero_id == $friend_id)
			return 'you';
		
		$esc_guerrero_id = $this->db->escape ($guerrero_id);
		$esc_friend_id   = $this->db->escape ($friend_id);
		
		$this->db->where    ('(`friend_send_gue_id` = '. $esc_guerrero_id .' AND `friend_receive_gue_id` = '. $esc_friend_id   .')');
		$this->db->or_where ('(`friend_send_gue_id` = '. $esc_friend_id   .' AND `friend_receive_gue_id` = '. $esc_guerrero_id .')');
		$result = $this->db->get ($this->friends_table);
		
		if (!$result)
			return 'model_error';
		if (!$result->num_rows)
			return 'no_invites';
		
		$friend_entries = $result->result();
		
		foreach ($friend_entries as $entry) {
			if ($entry->friend_send_gue_id == $guerrero_id)
				$my_invite = $entry;
			else
				$their_invite = $entry;
		}
		
		if (isset ($their_invite)) {
			if ($their_invite->friend_status == 0)
				return 'waiting_on_you';
			if ($their_invite->friend_status == -1)
				return 'you_ignored';
		}
		
		$status_strings = array (
			 2 => 'friends',
			 1 => 'friends',
			 0 => 'waiting_on_them',
			-1 => 'they_ignored'
		);
		
		return $status_strings[$my_invite->friend_status];
	}
	
	//----
	
	/**
	 * Guerrero friends
	 */
	public function guerrero_friends ($guerrero_id, $direction = 'sent', $type = 'all', $limit = 0)
	{
		if (empty ($guerrero_id) OR !is_numeric ($guerrero_id))
			return FALSE;
		
		switch ($direction) {
		default:
		case 'sent':
			$setup_type  = 'friend_list';
			$where_field = 'friend_send_gue_id';
			break;
		case 'received':
			$setup_type  = 'request_list';
			$where_field = 'friend_receive_gue_id';
			break;
		}
		
		switch ($type) {
		default:
		case 'all':
			break;
		case 'pending':
			$this->db->where ('friend_status',  0);
			break;
		case 'accepted':
			$this->db->where_in ('friend_status',  array (1, 2));
			break;
		case 'ignored':
			$this->db->where ('friend_status', -1);
			break;
		}
		
		$this->_guerrero_query_setup ($setup_type);
		$this->db->where ($where_field, $guerrero_id);
		if (!empty ($limit))
			$this->db->limit ($limit);
		$result = $this->db->get();

		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	
	/**
	 * Get Guerrero Countries
	 */
	public function guerrero_countries()
	{
		$this->db->distinct();
		$this->db->select ('guerrero_map_country');
		$this->db->from ($this->guerreros_table);
		$this->db->where ('guerrero_map_country IS NOT NULL');
		$this->db->order_by ('guerrero_map_country', 'ASC');
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		
		$guerrero_countries = array();
		foreach ($result->result() as $guerrero)
			$guerrero_countries[] = $guerrero->guerrero_map_country;
		
		return $guerrero_countries;
	}
	
	
	//----
	
	
	
	
	/**
	 * Get most popular places
	 */
	public function get_most_popular_places($limit = 0)
	{
		$this->db->select("
			(CASE guerrero_map_country
				when 'Commonwealth of Puerto Rico' then 'Puerto Rico' 
				when 'USA' then 'Estados Unidos'
				when 'United States' then 'Estados Unidos'
				when 'Brazil' then 'Brasil'
				when 'UK' then 'Reino Unido'
				when 'EEUU' then 'Estados Unidos'
				when 'Dominican Republic' then 'República Dominicana'
				when 'Japan' then 'Japón'
				when 'Italy' then 'Italia'
			ELSE guerrero_map_country END ) 'Spanish_Country', count(*) as Total");
		$this->db->from ($this->guerreros_table);
		$this->db->where('guerrero_map_country !=', ' ');
		$this->db->where('guerrero_id !=', 1);
		$this->db->group_by("Spanish_country");
		$this->db->order_by("Total", "DESC");
		if($limit != 0 ) 
		{
			$this->db->limit($limit);
		}
		$result = $this->db->get();

		if(!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	/**
	 * Get Messages count for a user
	 */
	public function message_count_by_guerrero ($guerrero_id, $type = 'all')
	{
		if ($type != 'all')
			$this->_message_where ($guerrero_id, $type);
		else
			$this->db->where ('message_gue_id', $guerrero_id);
		
		return $this->db->count_all_results ($this->messages_table);
	}
	
	//----
	
	/**
	 * Get Messages for a user
	 */
	public function messages_by_guerrero ($guerrero_id, $type = 'feed', $last_checked_date = '')
	{	
		$this->_message_where ($guerrero_id, $type);
		$this->db->select   (
			$this->messages_table .'.*,
			s.guerrero_id AS send_id, s.guerrero_name AS send_name, s.guerrero_avatar AS send_avatar, sl.legion_style AS send_legion, sr.rank_style AS send_rank,
			r.guerrero_id AS recv_id, r.guerrero_name AS recv_name, r.guerrero_avatar AS recv_avatar, rl.legion_style AS recv_legion, rr.rank_style AS recv_rank'
		);
		$this->db->from     ($this->messages_table);
		$this->db->join     ($this->guerreros_table .' AS s',  'message_gue_id           = s.guerrero_id');
		$this->db->join     ($this->guerreros_table .' AS r',  'message_recipient_gue_id = r.guerrero_id', 'LEFT');
		$this->db->join     ($this->legions_table   .' AS sl', 's.guerrero_legion_id     = sl.legion_id');
		$this->db->join     ($this->legions_table   .' AS rl', 'r.guerrero_legion_id     = rl.legion_id',  'LEFT');
		$this->db->join     ($this->ranks_table     .' AS sr', 's.guerrero_rank_id       = sr.rank_id');
		$this->db->join     ($this->ranks_table     .' AS rr', 'r.guerrero_rank_id       = rr.rank_id',    'LEFT');
		if (!empty ($last_checked_date))
			$this->db->where ('message_date <', $last_checked_date);
		$this->db->order_by ('message_date', 'DESC');
		$this->db->limit    (5);
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	/**
	 * Get Messages with pagination for a user
	 */
	
	
	public function messages_profile ($guerrero_id, $offset = 0, $type = 'timeline')
	{	
		$this->_message_where ($guerrero_id, $type);
		$this->db->select   (
			$this->messages_table .'.*,
			s.guerrero_id AS send_id, s.guerrero_name AS send_name, s.guerrero_avatar AS send_avatar, sl.legion_style AS send_legion, sr.rank_style AS send_rank,
			r.guerrero_id AS recv_id, r.guerrero_name AS recv_name, r.guerrero_avatar AS recv_avatar, rl.legion_style AS recv_legion, rr.rank_style AS recv_rank'
		);
		$this->db->from     ($this->messages_table);
		$this->db->join     ($this->guerreros_table .' AS s',  'message_gue_id           = s.guerrero_id');
		$this->db->join     ($this->guerreros_table .' AS r',  'message_recipient_gue_id = r.guerrero_id', 'LEFT');
		$this->db->join     ($this->legions_table   .' AS sl', 's.guerrero_legion_id     = sl.legion_id');
		$this->db->join     ($this->legions_table   .' AS rl', 'r.guerrero_legion_id     = rl.legion_id',  'LEFT');
		$this->db->join     ($this->ranks_table     .' AS sr', 's.guerrero_rank_id       = sr.rank_id');
		$this->db->join     ($this->ranks_table     .' AS rr', 'r.guerrero_rank_id       = rr.rank_id',    'LEFT');
		if (!empty ($last_checked_date))
			$this->db->where ('message_date <', $last_checked_date);
		$this->db->order_by ('message_date', 'DESC');
		$this->db->limit    (5, $offset);
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	
	/**
	 * Get All Ranks
	 */
	public function ranks()
	{		
		if (!$result = $this->db->get ($this->ranks_table))
			return FALSE;
		
		return $result->result();
	}
	
	//----
	
	/**
	 * Ranks for a user
	 */
	public function ranks_by_guerrero ($guerrero_id, $type = 'all')
	{
		if ($type == 'new')
			$this->db->where ('gr_status', FALSE);  // Get new (unviewed) ranks only
		
		$this->db->from  ($this->guerreros_ranks_table);
		$this->db->join  ($this->ranks_table, 'gr_rank_id = rank_id');
		$this->db->where ('gr_gue_id', $guerrero_id);
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	//----
	
	/**
	 * Get Rank By Id
	 */
	public function rank_by_id ($rank_id)
	{
	 	$this->db->where ('rank_id', $rank_id);
		$result = $this->db->get ($this->ranks_table);
		
		if (!$result)
			return FALSE;
		if (!$result->num_rows())
			return NULL;
		
		return current ($result->result());
	}
	 
	
	
	//----
	
	/**
	 * Old Authorizations
	 */
	public function old_authorizations()
	{
		$this->load->helper ('date');
		
		$this->db->where ("auth_date < DATE_SUB('". mnow() ."', INTERVAL 5 HOUR)");
		$result = $this->db->get ($this->auth_table);
		
		if (!$result)
			return FALSE;
		
		return $result->result();
	}
	
	
	//----
	
	
	/**
	 * Notifications for a user
	 */
	public function notifications_by_guerrero ($guerrero, $limit = 6, $last_checked_date = '', $last_registration_date = '')
	{
		$esc_guerrero_id = $this->db->escape ($guerrero->guerrero_id);
		
		// Get 
		$this->db->where    ('ticker_type', 2);
		$this->db->where    ('ticker_obj_id', $guerrero->legion_id);
		if (!empty ($last_registration_date))
		    $this->db->where ('ticker_date <', $last_registration_date);
		$this->db->order_by ('ticker_date', 'DESC');
		$this->db->limit    (1);
		$result = $this->db->get ($this->ticker_table);
		
		if (!$result)
			return FALSE;
		
		if ($next_registration = current ($result->result())) {
			$esc_gue_id    = $this->db->escape ($next_registration->ticker_gue_id);
			$esc_legion_id = $this->db->escape ($next_registration->ticker_obj_id);
			$esc_date      = $this->db->escape ($next_registration->ticker_date);
		}
		
		$this->db->select   (
			$this->ticker_table . '.*,
			UNIX_TIMESTAMP(ticker_date) AS ticker_date_stamp,
			guerrero_id, guerrero_avatar, (
				CASE WHEN guerrero_id != '. $esc_guerrero_id .' AND guerrero_is_name_private
					THEN guerrero_name
					ELSE guerrero_real_name
				END
			) AS guerrero_name'
		);
		$this->db->from     ($this->ticker_table);
		$this->db->join     ($this->guerreros_table, 'ticker_gue_id = guerrero_id');
		$this->db->where    ('(
			(`ticker_type`    = 4 AND `ticker_obj_id` = '. $esc_guerrero_id .')'. 
			(empty ($next_registration) ? '' : '
			OR (
				`ticker_type`       = 2
				AND `ticker_gue_id` = '. $esc_gue_id .'
				AND `ticker_obj_id` = '. $esc_legion_id .'
				AND `ticker_date`   = '. $esc_date .'
			)') .'
			OR (
				`ticker_type` IN (3, 5) AND (
					`ticker_gue_id` = '. $esc_guerrero_id .'
					OR `ticker_gue_id` IN ('. $this->_friend_subquery ($guerrero->guerrero_id, 'confirmed') .')
				)
			)
		)');
		if (!empty ($last_checked_date))
		    $this->db->where ('ticker_date <', $last_checked_date);
		
		$this->db->order_by ('ticker_date', 'DESC');
		$this->db->limit    ($limit);
		$result = $this->db->get();
		
		if (!$result)
			return FALSE;
		
		$notification_list = $result->result();
		
		foreach ($notification_list as $notification) {
			if ($notification->ticker_type == 2) {
				$this->db->select   ('
					ticker_date,
					guerrero_id, guerrero_avatar, (
						CASE WHEN guerrero_id != '. $esc_guerrero_id .' AND guerrero_is_name_private
							THEN guerrero_name
							ELSE guerrero_real_name
						END
					) AS guerrero_name'
				);
				$this->db->from     ($this->ticker_table);
				$this->db->join     ($this->guerreros_table, 'ticker_gue_id = guerrero_id');
				$this->db->where    ('ticker_type', 2);
				$this->db->where    ('ticker_obj_id', $notification->ticker_obj_id);
				$this->db->where    ("ticker_date > DATE_SUB('". $notification->ticker_date ."', INTERVAL 2 DAY)");
				$this->db->where    ('ticker_date <=', $notification->ticker_date);
				$this->db->order_by ('ticker_date', 'DESC');
				$result = $this->db->get();
				
				if (!$result)
					return FALSE;
				
				$notification->guerreros = $result->result();
				break;
			}
		}
		
		return $notification_list;
	}
	
	
	//----
	
	
	/**
	 * Get Old Ticker Data
	 */
	public function old_ticker ($item_type = '', $offset = 0)
	{
		switch ($item_type) {
		case 'registration':
			$this->db->select   ('ticker_date, guerrero_id, '. $this->legions_table .'.*');
			$this->db->from     ('ticker_history');
			$this->db->join     ($this->guerreros_table, 'ticker_registered_gue_id = guerrero_id');
			$this->db->join     ($this->legions_table,   'guerrero_legion_id = legion_id');
			$this->db->where    ('ticker_registered_gue_id IS NOT NULL');
			$this->db->order_by ('ticker_date', 'ASC');
			break;
		case 'achievement':
			$this->db->select   ('trophies.*, guerreros_trophies.*');
			$this->db->from     ('guerreros_trophies');
			$this->db->join     ('trophies', 'gt_trophy_id = trophy_id');
			$this->db->where    ('gt_noti_date IS NOT NULL');
			$this->db->where    ('gt_gue_id !=', 1);
			$this->db->order_by ('gt_noti_date', 'ASC');
			break;
		case 'invitation':
			$this->db->select   ('ticker_date, '. $this->friends_table .'.*');
			$this->db->from     ('ticker_history');
			$this->db->join     ($this->friends_table, 'ticker_invitation_id = friend_inv_id');
			$this->db->where    ('ticker_invitation_id IS NOT NULL');
			$this->db->where_in ('friend_status', array (-1, 0));
			$this->db->order_by ('ticker_date', 'ASC');
			break;
		case 'rank':
			$this->db->select   ($this->ranks_table .'.*, '. $this->guerreros_ranks_table .'.*');
			$this->db->from     ($this->guerreros_ranks_table);
			$this->db->join     ($this->ranks_table, 'gr_rank_id = rank_id');
			$this->db->where    ('gr_rank_id !=', 1);
			$this->db->where    ('gr_gue_id !=', 1);
			$this->db->order_by ('gr_id', 'ASC');
			break;
		default:
			break;
		}
		
		$this->db->limit (1000, $offset);
		if (!$result = $this->db->get())
			return FALSE;
		
		return $result->result();
	}
	
	//----
	
	/**
	 * Format New Ticker
	 */
	public function format_new_ticker ($item_type, $old_object, $timestamp)
	{
		$this->load->helper ('date');
		
		switch ($item_type) {
		case 'registration':
			return array (
				'ticker_type'       => 2,
				'ticker_gue_id'     => $old_object->guerrero_id,
				'ticker_obj_id'     => $old_object->legion_id,
				'ticker_obj_string' => lang ('app_'. $old_object->legion_tag .'_name'),
				'ticker_obj_style'  => $old_object->legion_style,
				'ticker_date'       => $old_object->ticker_date
			);
			break;
		case 'achievement':
			return array (
				'ticker_type'       => 3,
				'ticker_gue_id'     => $old_object->gt_gue_id,
				'ticker_obj_id'     => $old_object->trophy_id,
				'ticker_obj_string' => $old_object->trophy_name,
				'ticker_obj_style'  => $old_object->trophy_style,
				'ticker_date'       => $old_object->gt_noti_date
			);
			break;
		case 'invitation':
			return array (
				'ticker_type'       => 4,
				'ticker_gue_id'     => $old_object->friend_send_gue_id,
				'ticker_obj_id'     => $old_object->friend_receive_gue_id,
				'ticker_date'       => $old_object->ticker_date
			);
			break;
		case 'rank':
			return array (
				'ticker_type'       => 5,
				'ticker_gue_id'     => $old_object->gr_gue_id,
				'ticker_obj_id'     => $old_object->rank_id,
				'ticker_obj_string' => $old_object->rank_name,
				'ticker_obj_style'  => $old_object->rank_style,
				'ticker_date'       => empty ($old_object->gr_date) ? mnow ($timestamp) : $old_object->gr_date
			);
			break;
		default:
			return FALSE;
			break;
		}
	}
	
	
	
	// !Database Write Methods
	//------------------------------------------
	
	/**
	 * Update Guerrero
	 */
	public function update_guerrero ($guerrero_data, $guerrero_id = 0)
	{
		$this->load->helper ('date');
		
		if (empty ($guerrero_data)
			OR !is_array ($guerrero_data)
			OR !is_numeric ($guerrero_id))
				return FALSE;
		
		$mysql_now = mnow();
		
		if ((isset ($guerrero_data['recovery']) OR is_null ($guerrero_data['recovery'])) && !empty ($guerrero_id))
		{ 
			$this->db->where ('recover_guerrero_id', $guerrero_id);
			$result = $this->db->get ($this->password_recover_table);
			
			if (!$result)
				return FALSE;
			
			$recover_data['recover_token'] = $guerrero_data['recovery'];
			
			// Update expiration unless we are just invalidating the token
			if (!empty ($guerrero_data['recovery']))
				$recover_data['recover_expiration'] = mnow (strtotime ('+1 day'));
			
			if (!$result->num_rows) {
				$recover_data['recover_guerrero_id'] = $guerrero_id;
				$result = $this->db->insert ($this->password_recover_table, $recover_data);
			}
			else {
				$this->db->where ('recover_guerrero_id', $guerrero_id);
				$result = $this->db->update ($this->password_recover_table, $recover_data);
			}
			
			if (!$result)
				return FALSE;
			
			unset ($guerrero_data['recovery']);
		}
		
		if (!isset ($guerrero_data['guerrero_modified']))
			$guerrero_data['guerrero_modified'] = $mysql_now;
		
		if (isset ($guerrero_data['guerrero_last_login']) && empty ($guerrero_data['guerrero_last_login']))
			$guerrero_data['guerrero_last_login'] = $mysql_now;
		
		
		if (empty ($guerrero_id))
		{
			$guerrero_data['guerrero_created'] = $mysql_now;
			
			if (!$this->db->insert ($this->guerreros_table, $guerrero_data))
				return FALSE;
			
			$guerrero_id = $this->db->insert_id();
			$this->write_friends ($guerrero_id, 1, 'ricky');
			$this->write_ranks ($guerrero_id, 1);
			$this->_add_ticker_item ('registration', $guerrero_id, $guerrero_data['guerrero_legion_id']);
			
			return $guerrero_id;
		}
		else {
			$this->db->where ('guerrero_id', $guerrero_id);
			return $this->db->update ($this->guerreros_table, $guerrero_data);
		}
	}
	
	//----
	
	/**
	 * Write Friends
	 */
	public function write_friends ($logged_user, $other_user, $type)
	{
		switch ($type) {
		case 'accept':
			return $this->update_friends ($other_user, $logged_user, 'accepted');
			break;
		case 'invite':
			return $this->update_friends ($logged_user, $other_user, 'pending');
			break;
		case 'ricky':
			$ricky_is_friends_with_everyone = array (
				array (
					'friend_send_gue_id'    => $logged_user,
					'friend_receive_gue_id' => 1,
					'friend_status'         => 1
				),
				array (
					'friend_send_gue_id'    => 1,
					'friend_receive_gue_id' => $logged_user,
					'friend_status'         => 2
				)
			);
			return $this->db->insert_batch ($this->friends_table, $ricky_is_friends_with_everyone);
			break;
		default:
			break;
		}
	}
	
	//----
	
	/**
	 * Update Friends
	 */
	public function update_friends ($sender, $receiver, $status = 'pending')
	{
		if (empty ($sender)      OR !is_numeric ($sender)
			OR empty ($receiver) OR !is_numeric ($receiver)
			OR empty ($status)   OR !in_array ($status, array ('pending', 'accepted', 'ignored')))
				return FALSE;
		
		switch ($status) {
		default:
		case 'pending':
			$this->db->where ('friend_send_gue_id',    $sender);
			$this->db->where ('friend_receive_gue_id', $receiver);
			$result = $this->db->get ($this->friends_table);
			
			if ($result && $result->num_rows)
				return TRUE;
			
			$new_request                          = TRUE;
			$friend_data['friend_send_gue_id']    = $sender;
			$friend_data['friend_receive_gue_id'] = $receiver;
			break;
		case 'accepted':
			$reciprocal_add = array (
				'friend_send_gue_id'    => $receiver,
				'friend_receive_gue_id' => $sender
			);
			
			$this->db->where ($reciprocal_add);
			$this->db->delete ($this->friends_table);
			
			$reciprocal_add['friend_status'] = 2;
			$this->db->insert ($this->friends_table, $reciprocal_add);
			$this->_remove_ticker_item ('invitation', $sender, $receiver);
			
			$new_request                  = FALSE;
			$friend_data['friend_status'] = 1;
			break;
		case 'ignored':
			$new_request                  = FALSE;
			$friend_data['friend_status'] = -1;
			break;
		}
		
		if ($new_request)
		{
			if (!$this->db->insert ($this->friends_table, $friend_data))
				return FALSE;
			
			$invite_id = $this->db->insert_id();
		
			if (!$this->_add_ticker_item ('invitation', $sender, $receiver)) {
				$this->db->where ('friend_inv_id', $invite_id);
				$this->db->delete ($this->friends_table);
				return FALSE;
			}
			return TRUE;
		}
		else {
			$this->db->where ('friend_send_gue_id',    $sender);
			$this->db->where ('friend_receive_gue_id', $receiver);
			return $this->db->update ($this->friends_table, $friend_data);
		}
	}
	
	
	//----
	
	/**
	 * Get ALL trophies list
	 */
	public function trophies($guerrero_id)
	{
			
		$this->db->join ("(
				SELECT DISTINCT(`gt_trophy_id`) AS `has_trophy`
				FROM   `guerreros_trophies`
				WHERE  (`gt_gue_id` = ". $guerrero_id ." )
			) AS `t`", 'trophy_id = t.has_trophy', 'left');
		
		
		$this->db->order_by('trophy_id', 'ASC');
		
		$result = $this->db->get('trophies');
		
		return $result ->result();

	}
	
	
	/**
	 * Get ALL Trophies by user
	 */
	public function trophies_by_guerrero($guerrero_id)
	{
	
		$this->db->where('gt_gue_id', $guerrero_id);
		$this->db->from('guerreros_trophies');
		$this->db->join('trophies', 'gt_trophy_id = trophy_id');
		$this->db->where ('gt_status', TRUE);
		$result = $this->db->get();
		
		return (object) array(
			'count_t' => count($result->result()),
			'trophy_list' => $result->result()
		);

	}
	//----
	
	/**
	 * Get ALL Trophies by user
	 */
	public function trophy_by_id ($trophy_id)
	{
		$this->db->where ('trophy_id', $trophy_id);
		$result = $this->db->get ('trophies');
		
		if (!$result)
			return FALSE;
		if (!$result->num_rows())
			return NULL;
		
		return current ($result->result());
	}
	
	//----
	
	
	public function count_guerreros_messages ($guerreros_ids)
	{	
		$this->db->select('guerrero_id, COUNT(message_id)  AS  `messages_total`');
		$this->db->from('guerreros');
		$this->db->join( 'messages','guerrero_id = message_gue_id','left');
		$this->db->group_by('guerrero_id');	
		$this->db->where_in('guerrero_id', $guerreros_ids);
		$result = $this->db->get();
		
		foreach($result->result() as $r)
		{
			$m_counts[$r->guerrero_id] = $r->messages_total;
		}
		return $m_counts;
	}
	
	
	public function count_guerreros_trophies ($guerreros_ids, $backend='')
	{
		if($backend) //count including the ones whithout notifications
		{
			$this->db->select('guerrero_id, COUNT(CASE WHEN `gt_status` = 1 THEN 1 ELSE 1 END)  AS  `trophies_total`');
		}
		else{		
			$this->db->select( 'guerrero_id, COUNT(CASE WHEN `gt_status` = 1 THEN 1 ELSE NULL END)  AS  `trophies_total`');
		}
		$this->db->from('guerreros');
		$this->db->join( 'guerreros_trophies','guerrero_id = gt_gue_id','left');
		$this->db->group_by('guerrero_id');	
		$this->db->where_in('guerrero_id', $guerreros_ids);
		$result = $this->db->get();
		
		foreach($result->result() as $r)
		{
			$t_counts[$r->guerrero_id] = $r->trophies_total;
		}
		return $t_counts;
	}
	
	public function new_trophies($guerrero_id, $guerrero_name, $guerrero_email)
	{
		$this->load->helper ('date');
		$this->load->library ('email');
		$this->lang->load ('social');
		
		$this->db->where('gt_gue_id', $guerrero_id);
		$this->db->where('gt_status', 0);
		$this->db->from('guerreros_trophies');
		$this->db->join('trophies', 'gt_trophy_id = trophy_id');
		$result = $this->db->get();
		
		$trophies = $result->result();
		
		foreach($trophies as $trophy)
		{
			$data = array(
				'gt_status' => 1
			);
			
			$data['gt_noti_date'] = mnow();
			$this->db->where('gt_id', $trophy->gt_id );
			$this->db->update('guerreros_trophies', $data);
			
			$this->_add_ticker_item ('achievement', $guerrero_id, $trophy->gt_trophy_id);
			
			$this->email->initialize (array ('mailtype'=>'text'));
			$this->email->subject    ('Haz ganado una Recompensa - Guerreros de Luz');
			$this->email->message    ($this->load->view ('social/email_recompensas_view', array ('trophy'=>$trophy), TRUE));
			$this->email->from       ('auto@guerrerosdeluz.org', lang('app_client'));
			$this->email->to         ('"'.$guerrero_name.'" <'.$guerrero_email.'>');
			$this->email->send();
		
		}
		
		return $trophies;
	}
	
	
	/**
	 * Write Trophies
	 */
	public function update_trophies($guerrero_id, $rank, $trophy_style)
	{
		
		$this->db->select('trophy_id');
		$this->db->where('trophy_style', $trophy_style);
		$result= $this->db->get('trophies');
		$trophy = $result->result();
		
		$this->db->where('gt_trophy_id', $trophy[0]->trophy_id);
		$this->db->where('gt_gue_id',$guerrero_id );
		$has_t = $this->db->get('guerreros_trophies');
		
				
		switch($trophy_style)
		{
			case 'gue':
			break;
			case 'super':
			
				$this->db->where('message_gue_id',$guerrero_id);
				$r = $this->db->get('messages');	
				$m = $r->result();
			
				if(count($m) >= 25)
				{
					if(!$has_t->result())
					{
						$data  = array(
							'gt_trophy_id'	=>$trophy[0]->trophy_id,
							'gt_gue_id' 	=>$guerrero_id
						);
						$this->db->insert('guerreros_trophies', $data );
					}
				}
			break;
			default:
				if($has_t->num_rows() == 0) 
				{ 
					$data  = array(
						'gt_trophy_id'	=>$trophy[0]->trophy_id,
						'gt_gue_id' 	=>$guerrero_id
					);
					$this->db->insert('guerreros_trophies', $data );
				}
			break;
				
		}
		
		
		
		if($rank > 1){
			
			$this->db->where('gt_gue_id', $guerrero_id);
			$this->db->where_not_in('gt_trophy_id', array( 5, 7));
			$pre_result = $this->db->get('guerreros_trophies');
			$result_trophies = $pre_result->result();
			
			if(count($result_trophies) == 4 )
			{
				$columns = array(
					'gt_trophy_id' => 1,
					'gt_gue_id'    =>$guerrero_id
				);
				$this->db->insert('guerreros_trophies', $columns);
			}
			
		}

	}
	//____
	
	/**
	 * Write Messages
	 */
	public function write_messages ($text, $guerrero_id, $receiver_id = 0)
	{
		$this->load->helper ('date');
		
		if (empty ($text)           OR !is_string ($text)
			OR empty ($guerrero_id) OR !is_numeric ($guerrero_id))
				return FALSE;
		
		$message_array['message_text']   = $text;
		$message_array['message_gue_id'] = $guerrero_id;
		$message_array['message_date']   = mnow();
		
		if (!empty ($receiver_id))
			$message_array['message_recipient_gue_id'] = $receiver_id;
		
		return $this->db->insert ($this->messages_table, $message_array);
	}
	
	
	//----
	
	
	/**
	 * Write Ranks
	 */
	public function write_ranks ($guerrero_id, $rank_id = 0, $extra_actions = '')
	{
		$this->load->helper ('date');
		
		if (empty ($guerrero_id) OR !is_numeric ($guerrero_id))
			return FALSE;
		
		$mysql_now = mnow();
		
		if (!empty ($rank_id) && is_numeric ($rank_id)) {
			$rank_array['gr_gue_id']  = $guerrero_id;
			$rank_array['gr_rank_id'] = $rank_id;
			$rank_array['gr_date']    = $mysql_now;
			
			if ($rank_id == 1)
				$rank_array['gr_status'] = TRUE;
			
			if (!$this->db->insert ($this->guerreros_ranks_table, $rank_array))
				return FALSE;
			
			$rank_gain_id = $this->db->insert_id();
		}
		
		if (!empty ($extra_actions))
		{
			if (in_array ('add_ticker', $extra_actions)) {
				if (!$this->_add_ticker_item ('rank', $guerrero_id, $rank_id)) {
					$this->db->where ('gr_id', $rank_gain_id);
					$this->db->delete ($this->guerreros_ranks_table);
					return FALSE;
				}
			}
			
			if (in_array ('update_user', $extra_actions)) {
				$this->db->where ('guerrero_id', $guerrero_id);
				if (!$this->db->update ($this->guerreros_table, array ('guerrero_rank_id'=>$rank_id)))
					return FALSE;
			}
			
			if (in_array ('update_status', $extra_actions)) {
				$this->db->where ('gr_gue_id', $guerrero_id);
				$this->db->where ('gr_status', FALSE);
				if (!$this->db->update ($this->guerreros_ranks_table, array ('gr_status'=>TRUE, 'gr_date'=>$mysql_now)))
					return FALSE;
			}
		}
		
		return TRUE;
	}
	
	//----
	
	/**
	 * Write Authorizations
	 */
	public function write_authorizations ($auth_pnref)
	{
		$this->load->helper ('date');
		
		if (empty ($auth_pnref) OR strlen ($auth_pnref) > 12)
			return FALSE;
		
		$this->db->where ('auth_pnref', $auth_pnref);
		$result = $this->db->get ($this->auth_table);
		
		if (!$result)
			return FALSE;
		
		$auth_entry['auth_date'] = mnow();
		
		if ($result->num_rows()) {
			$this->db->where ('auth_pnref', $auth_pnref);
			return $this->db->update ($this->auth_table, $auth_entry);
		}
		else {
			$auth_entry['auth_pnref'] = $auth_pnref;
			return $this->db->insert ($this->auth_table, $auth_entry);
		}
	}
	
	//----
	
	/**
	 * Delete Authorizations
	 */
	public function delete_authorizations ($auth_pnref_or_array)
	{
		$is_single = is_string ($auth_pnref_or_array);
		$is_batch  = !$is_single && is_array  ($auth_pnref_or_array);
		
		if (empty ($auth_pnref_or_array)
			OR (!$is_single && !$is_batch)
			OR ($is_single  && strlen ($auth_pnref_or_array) > 12)
			OR ($is_batch   && !count ($auth_pnref_or_array)))
				return FALSE;
		
		if ($is_single)
			$this->db->where ('auth_pnref', $auth_pnref_or_array);
		else if ($is_batch)
			$this->db->where_in ('auth_pnref', $auth_pnref_or_array);
		
		return $this->db->delete ($this->auth_table);
	}
	
	//----
	
	/**
	 * Write New Ticker
	 */
	public function write_new_ticker ($entries_array)
	{
		if (empty ($entries_array) OR !is_array ($entries_array))
			return FALSE;
		
		return $this->db->insert_batch ($this->ticker_table, $entries_array);
	}
	
	
	
	// !Private Methods
	//------------------------------------------
	
	/**
	 * Add Ticker Item
	 */
	public function _add_ticker_item ($item_type, $guerrero_id, $object_id)
	{
		if (empty ($guerrero_id)  OR !is_numeric ($guerrero_id)
		    OR empty ($object_id) OR !is_numeric ($object_id))
				return FALSE;
		
		$this->load->helper ('date');
		
		switch ($item_type) {
		case 'message':
			$ticker_type = 1;
			break;
		case 'registration':
			// Get legion info
			if (!$legion = $this->legion_by_id ($object_id))
				return FALSE;
			
			$new_ticker_item['ticker_obj_string'] = lang ('app_'. $legion->legion_tag .'_name');
			$new_ticker_item['ticker_obj_style']  = $legion->legion_style;
			$ticker_type = 2;
			break;
		case 'achievement':
			// Get achievement info
			if (!$trophy = $this->trophy_by_id ($object_id))
				return FALSE;
			
			$new_ticker_item['ticker_obj_string'] = $trophy->trophy_name;
			$new_ticker_item['ticker_obj_style']  = $trophy->trophy_style;
			$ticker_type = 3;
			break;
		case 'invitation':
			// Don't get anything
			$ticker_type = 4;
			break;
		case 'rank':
			// Get rank info
			if (!$trophy = $this->rank_by_id ($object_id))
				return FALSE;
			
			$new_ticker_item['ticker_obj_string'] = $trophy->rank_name;
			$new_ticker_item['ticker_obj_style']  = $trophy->rank_style;
			$ticker_type = 5;
			break;
		default:
			$ticker_type = FALSE;
			break;
		}
		
		if (empty ($ticker_type) OR !is_numeric ($ticker_type))
			return FALSE;
		
		$new_ticker_item['ticker_type']   = $ticker_type;
		$new_ticker_item['ticker_gue_id'] = $guerrero_id;
		$new_ticker_item['ticker_obj_id'] = $object_id;
		$new_ticker_item['ticker_date']   = mnow();
		
		return $this->db->insert ($this->ticker_table, $new_ticker_item);
	}
	
	
	//----
	
	
	
	
	/**
	 * Insert Money
	 */
	 public function insert_money($money)
	 {
	 	$result = $this->db->insert ('money_recolected', $money);
	 	return $result;	 
	 }
	
	/**
	 * Get Earned Money by day
	 */	
	 
	 public function get_money_recolected_by_day()
	 {
	 	$result = $this->db->get('money_recolected'); 	
	 	return $result->result();
	 }
	 
	 /**
	 * Get first day data and last day data money
	 */	
	 public function get_first_last_money()
	 {
	 	$result= $this->db->query('(SELECT * FROM `money_recolected` ORDER BY id ASC LIMIT 1) UNION (SELECT * FROM `money_recolected` ORDER BY id DESC limit 1)');
	 		 
	 	return $result->result();
	 }
	 
	 public function get_last_seven_money_entries()
	 {	
	 	$this->db->order_by('id', 'DESC');
	 	$this->db->limit(7);
	 	$result = $this->db->get('money_recolected');
	  	$result_array = array_reverse ($result->result());
	  	return $result_array;
	 }
	 
	
	/**
	 * Remove Ticker Item
	 */
	private function _remove_ticker_item ($ticker_type, $ticker_gue_id, $ticker_obj_id)
	{
		if (empty ($ticker_type)      OR !is_string  ($ticker_type)
			OR empty ($ticker_gue_id) OR !is_numeric ($ticker_gue_id)
			OR empty ($ticker_obj_id) OR !is_numeric ($ticker_obj_id))
				return FALSE;
		
		switch ($ticker_type) {
		case 'message':
			$ticker_type = 1;
			break;
		case 'registration':
			$ticker_type = 2;
			break;
		case 'achievement':
			$ticker_type = 3;
			break;
		case 'invitation':
			$ticker_type = 4;
			break;
		case 'rank':
			$ticker_type = 5;
			break;
		}
		
		$this->db->where ('ticker_type',   $ticker_type);
		$this->db->where ('ticker_gue_id', $ticker_gue_id);
		$this->db->where ('ticker_obj_id', $ticker_obj_id);
		return $this->db->delete ($this->ticker_table);
	}
	
	//----
	
	/**
	 * Guerrero Query Setup
	 */
	private function _guerrero_query_setup ($type = 'default')
	{
		switch ($type) {
		default:
		case 'default':
			$friend_count = TRUE;
			$friend_join  = 'friend_send_gue_id';
			
			break;
		case 'friend_list':
			$friend_count = FALSE;
			$trophy_count = FALSE;
			$friend_join  = 'friend_receive_gue_id';
			break;
		case 'request_list':
			$friend_count = FALSE;
			$trophy_count = FALSE;
			$friend_join  = 'friend_send_gue_id';
		}
		
		$this->db->select ('`'. $this->guerreros_table .'`.*,
			UNIX_TIMESTAMP(`guerrero_birthday`)   AS `guerrero_birthday_stamp`,
			UNIX_TIMESTAMP(`guerrero_created`)    AS `guerrero_created_stamp`,
			UNIX_TIMESTAMP(`guerrero_modified`)   AS `guerrero_modified_stamp`,
			UNIX_TIMESTAMP(`guerrero_last_login`) AS `guerrero_last_login_stamp`,
			`'. $this->legions_table .'`.*,
			`'. $this->ranks_table .'`.*,
			'.(!$friend_count ? '' :	// 1 = Friend request accepted
			'COUNT(CASE
			 	WHEN `friend_status` IN (1, 2) THEN 1
			 	ELSE NULL
			 END) AS `friend_total`'), FALSE
		);
		$this->db->from ($this->guerreros_table);
		$this->db->join ($this->legions_table, 'guerrero_legion_id = legion_id');
		$this->db->join ($this->ranks_table, 'guerrero_rank_id = rank_id');
		$this->db->join ($this->friends_table, 'guerrero_id = '. $friend_join, 'left');
		
		if ($friend_count) {
			$this->db->group_by ('guerrero_id');
		}
	}
	
	
	//----
	
	
	/**
	 * Guerrero query setup for map use
	 */
	
	private function _guerrero_map_query_setup()
	{	
		$friend_count = TRUE;
		$this->db->select ('`'. $this->guerreros_table .'`. guerrero_id, guerrero_avatar, guerrero_map_town,guerrero_map_country, guerrero_geo_lat, guerrero_geo_long,guerrero_legion_id,guerrero_rank_id, guerrero_town, guerrero_country, (
				CASE WHEN guerrero_id != 1 AND guerrero_is_name_private
					THEN guerrero_name
					ELSE guerrero_real_name
				END
			) AS guerrero_name,
			UNIX_TIMESTAMP(`guerrero_birthday`)   AS `guerrero_birthday_stamp`,
			UNIX_TIMESTAMP(`guerrero_created`)    AS `guerrero_created_stamp`,
			UNIX_TIMESTAMP(`guerrero_modified`)   AS `guerrero_modified_stamp`,
			UNIX_TIMESTAMP(`guerrero_last_login`) AS `guerrero_last_login_stamp`,
			`'. $this->legions_table .'`.*,
			`'. $this->ranks_table .'`.*,
			'.(!$friend_count ? '' :	// 1 = Friend request accepted
			'COUNT(CASE
			 	WHEN `friend_status` IN (1, 2) THEN 1
			 	ELSE NULL
			 END) AS `friend_total`'), FALSE
		);
		$this->db->from ($this->guerreros_table);
		$this->db->join ($this->legions_table, 'guerrero_legion_id = legion_id');
		$this->db->join ($this->ranks_table, 'guerrero_rank_id = rank_id');
		$this->db->join ($this->friends_table, 'guerrero_id =  friend_send_gue_id', 'left');
		
		$this->db->group_by ('guerrero_id');	
	}
	
	//----
	
	/**
	 * Friend Subquery
	 */
	private function _friend_subquery ($guerrero_id, $type = 'confirmed', $in_field = '')
	{
		if (empty ($guerrero_id) OR !is_numeric ($guerrero_id))
			return FALSE;
		
		$esc_guerrero_id = $this->db->escape ($guerrero_id);
		$subquery        = "
			SELECT `friend_receive_gue_id`
			FROM   `friends`
			WHERE  `friend_send_gue_id` = ". $esc_guerrero_id . "
				AND `friend_status` IN (". ($type != 'pending' ? '' : '0, ') ."1, 2)
		";
		
		if (empty ($in_field))
			return $subquery;
		
		$this->db->where ('`'. $in_field .'` NOT IN ('. $subquery .')');
		
		return TRUE;
	}
	
	
	//----
	
	
	/**
	 * Message Where Clauses
	 */
	private function _message_where ($guerrero_id, $type = 'feed')
	{
		$esc_guerrero_id = $this->db->escape ($guerrero_id);
		
		switch ($type) {
		case 'feed':      // Like Facebook's home feed (all your messages, friends' status messages and those directed to you)
			$this->db->where ('((
					`message_gue_id` = '. $esc_guerrero_id .'
				)
				OR (
				`message_gue_id` IN (
					SELECT `friend_receive_gue_id`
					FROM   `friends`
					WHERE  `friend_send_gue_id` = '. $esc_guerrero_id .'
						AND `friend_status` IN (1, 2)
				)
				AND (
					`message_recipient_gue_id` IS NULL
						OR `message_recipient_gue_id` = '. $esc_guerrero_id .'
				)
			))');
			break;
		case 'timeline':  // Like Facebook's timeline (your status messages and messages from friends directed to you)
		default:
			$this->db->where ('((
				`message_gue_id` = '. $esc_guerrero_id .'
					AND `message_recipient_gue_id` IS NULL
			)
			OR (
				`message_gue_id` IN (
					SELECT `friend_receive_gue_id`
					FROM   `friends`
					WHERE  `friend_send_gue_id` = '. $esc_guerrero_id .'
						AND `friend_status` IN (1, 2)
				)
				AND `message_recipient_gue_id` = '. $esc_guerrero_id .'
			))');
			break;
		}
	}
	//----
	
	
	/**
	 * User Options
	 */
	 
	 public function delete_user($user_id)
	 {
	 	if($result = $this->db->delete('guerreros', array('guerrero_id' => $user_id)))
	 		return TRUE;
	 }
	 public function update_status($user_id, $i)
	 {

	 	if ($i == 1)
	 		$data = array ('guerrero_status' => 'ACTIVO');
	 	else
	 		$data = array ('guerrero_status' => 'DESACTIVO');
	 	$this->db->where('guerrero_id', $user_id);
		$this->db->update('guerreros', $data); 	
	 	
	 }
}


/* End of file guerrero_model.php */
/* Location: ./application/model/guerrero_model.php */