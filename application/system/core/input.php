<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );
/**
 * $Rev$
 * $Date$
 * $HeadURL$
 */

// @todo: Certainly we need a lot of protection

class LIM_Input {
	
	private $GET, $POST;
	
	## ---------------------------------------------------------------
	
	public function __construct()
	{
		$this->_post	= $_POST;
		$this->_get		= $_GET;
		unset($_POST, $_GET);
	}
	
	## ---------------------------------------------------------------
	
	public function post($key = '')
	{
		if( isset($this->_post[$key]) )
			return $this->_post[$key];
		return FALSE;
	}
	
	## ---------------------------------------------------------------
	
	public function get($key = '')
	{
		if( isset($this->_get[$key]) )
			return $this->_get[$key];
		return FALSE;
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file input.php */
/* Location: ./system/core/input.php */