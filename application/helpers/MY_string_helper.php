<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Escape JavaScript
 */

if ( ! function_exists('escape_js'))
{
	function escape_js ($js_string)
	{
		if (empty ($js_string) && $js_string !== 0)
			return '';
		
		$js_string = str_replace ("'", "\'", $js_string);
		
		return $js_string;
	}
}


//----


/**
 * Escape CSV
 */

if ( ! function_exists('escape_csv'))
{
	function escape_csv ($csv_string)
	{
		if (empty ($csv_string) && $csv_string !== 0)
			return '';
		
		$csv_string = str_replace ('"', '""', $csv_string);							// First off escape all " and make them ""
		
		if (preg_match ('/,/', $csv_string) OR preg_match ('/\n/', $csv_string)		// Check if any commas, new lines,
			OR preg_match ('/"/', $csv_string) OR preg_match ('/ /', $csv_string))	// spaces or quotes
				return '"'.$csv_string.'"';											// If so, escape them
		else
			return $csv_string;														// Else just return the value
	}
}

//----

/**
 * Replace Latin Characters
 */

if ( ! function_exists('replace_latin'))
{
	function replace_latin ($latin_string)
	{
		if (empty ($latin_string))
			return '';
		
		$translations	= array (
			'Á'	=> 'A',
			'á'	=> 'a',
			'É'	=> 'E',
			'é'	=> 'e',
			'Í'	=> 'I',
			'í'	=> 'i',
			'Ó'	=> 'O',
			'ó'	=> 'o',
			'Ú'	=> 'U',
			'ú'	=> 'u',
			'Ñ'	=> '~N',
			'ñ'	=> '~n',
			'¡'	=> '',
			'¿'	=> ''
		);
		
		return strtr ($latin_string, $translations);
	}
}

//----

/**
 * Latin CSV
 */

if ( ! function_exists('latin_csv'))
{
	function latin_csv ($latin_string)
	{
		if (empty ($latin_string))
			return '';
		
		return escape_csv (replace_latin ($latin_string));
	}
}


/* End of file MY_string_helper.php */
/* Location: ./application/helpers/MY_string_helper.php */