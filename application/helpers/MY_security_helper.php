<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Encrypt Password
 */
if ( ! function_exists('encrypt_password'))
{
	function encrypt_password ($password)
	{
		$unique_salt = substr (sha1 (mt_rand()), 0, 22);
		
		return crypt ($password,
			'$2a' . // blowfish
			'$10' . // cost parameter
			'$'   . $unique_salt);
	}
}

//----

/**
 * Check Password
 */
if ( ! function_exists('check_password'))
{
	function check_password ($password, $hash)
	{
		if (empty ($password) OR !is_string ($password)
		    OR empty ($hash)  OR !is_string ($hash))
		    	return FALSE;
		
		$full_salt = substr ($hash, 0, 29);
		$new_hash  = crypt  ($password, $full_salt);
		
		return ($new_hash == $hash);
	}
}


/* End of file MY_security_helper.php */
/* Location: ./application/helpers/MY_security_helper.php */