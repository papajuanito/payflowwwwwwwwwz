<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Get "now" time in MySQL format
 *
 * Returns 'YYYY-MM-DD HH:MM:SS' using sent timestamp or CI now() function
 *
 * @access	public
 * @param	integer	UNIX timestamp to generate the date string with
 * @return	string
 */

if ( ! function_exists('mnow'))
{
	function mnow ($time = '')
	{
		if ($time == '')
			$time = now();

		return date ('Y-m-d H:i:s', $time);
	}
}



if( ! function_exists('shortDate'))
{
	function shortDate ($timeStamp = null, $return = 'now')
	{
		setlocale (LC_ALL, 'es_ES');
		
		if (empty ($timeStamp))
			if ($return == 'now')
				$timeStamp = time();
			else
				return '';
		
		$day	= date		('j',	$timeStamp);
		$year	= date		('Y',	$timeStamp);
		$month	= strftime	('%b',	$timeStamp);
		
		return $day . ' / ' . $month . ' / ' . $year;
	}
}


if( ! function_exists('long_date'))
{
	function long_date ($time_stamp = null, $return = 'now')
	{
		setlocale (LC_ALL, 'es_ES');
		
		if (empty ($time_stamp))
			if ($return == 'now')
				$time_stamp = time();
			else
				return '';
		
		$day   = date     ('j',  $time_stamp);
		$year  = date     ('Y',  $time_stamp);
		$month = strftime ('%B', $time_stamp);
		
		return $day . ' de ' . $month . ' de ' . $year;
	}
}


if( !function_exists('shortDateToTextDate'))
{
	function shortDateToTextDate ($shortDate)
	{
		$date = explode("/", $shortDate);
		$day = $date[0];
		$year= $date[2];
		$month=$date[1];
	
		return $year.'-'.$month.'-'.$day;
	
	}
}


//----


/**
 * Validate Date
 * - Expected format yyyy-mm-dd (MySQL)
 */
if( !function_exists('valid_date'))
{
	function valid_date ($date)
	{
		$date_parts = explode ('-', $date);
		$month      = $date_parts[1];
		$day        = $date_parts[2];
		$year       = $date_parts[0];
		
		return @checkdate ($month, $day, $year);
	}
}


/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */
