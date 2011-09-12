<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class LIM_Loader {
	
	private $view_data = array();
	
	public function __construct()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	public function view($view, $data = array())
	{
		$view_file = APPPATH . 'views' . DS . $view . '_view.php';
		if( ! file_exists($view_file) )
			exit('View file not found! ' . $view_file);
		
		$this->view_data = array_merge($this->view_data, $data);
		extract($this->view_data);
		
		include_once( $view_file );
	}
	
	## ---------------------------------------------------------------
	
	public function helper()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	public function model()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	public function library()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	public function config()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	private function _load_file()
	{
		
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file loader.php */
/* Location: ./system/core/loader.php */