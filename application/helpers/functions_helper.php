<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );
/**
 * $Rev$
 * $Date$
 * $HeadURL$
 */

## ---------------------------------------------------------------

if( ! function_exists('printr') )
{
	function printr($array)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
}

## ---------------------------------------------------------------

/* End of file functions_helper.php */
/* Location: ./application/helpers/functions_helper.php */