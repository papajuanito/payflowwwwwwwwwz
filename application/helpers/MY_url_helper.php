<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Encode URI String
 *
 * Returns the URI segments with URI encoding and forward slashes (/) replaced by three carets (^^^)
 * to work around Apache settings that don't allow encoded forward slashes in URIs.
 *
 * @access	public
 * @param	string	the URI to be encoded
 * @return	string
 */

if ( ! function_exists('encode_uri_string'))
{
	function encode_uri_string ($uri_string = '')
	{
		$uri_string = $uri_string === '' ? uri_string() : $uri_string;
		$uri_string = str_replace ('/', '>>>', $uri_string);
		$uri_string = urlencode ($uri_string);
		
		return $uri_string;
	}
}


// ------------------------------------------------------------------------
/**
 * Decode URI String
 *
 * Returns the URI segments decoded and with triple carets (^^^) replaced by forward slashes (/)
 * to work around Apache settings that don't allow encoded forward slashes in URIs.
 *
 * @access	public
 * @param	string	the URI to be decoded
 * @return	string
 */

if ( ! function_exists('decode_uri_string'))
{
	function decode_uri_string ($uri_string = '')
	{
		if (empty ($uri_string))
			return FALSE;
		
		$uri_string = urldecode ($uri_string);
		$uri_string = str_replace ('>>>', '/', $uri_string);
		
		return $uri_string;
	}
}


if ( ! function_exists('avatar_url'))
{
	function avatar_url ($guerrero_avatar)
	{
		if (empty ($guerrero_avatar))
			return base_url ('img/avatar_sample_big.jpg');
		if (is_numeric ($guerrero_avatar))
			return base_url ('img/avatars/default_'. $guerrero_avatar .'.jpg');
		
		return base_url ('uploads/avatars/' . $guerrero_avatar);
	}
}


/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */