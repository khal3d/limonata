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
			show_error('View file not found! ' . $view_file, __FILE__, __LINE__);
		
		$this->view_data = array_merge($this->view_data, $data);
		extract($this->view_data);
		
		include_once( $view_file );
	}
	
	## ---------------------------------------------------------------
	
	public function helper($helpers)
	{
		if( ! is_array($helpers) && is_string($helpers) )
			$helpers = array($helpers);
		
		foreach($helpers as $helper)
		{
			$helper_file = APPPATH . 'helpers' . DS . $helper . '_helper.php';
			
			if( ! file_exists($helper_file) )
				show_error("Helper file {$helper_file} dosn't exists");
			
			include_once($helper_file);
		}
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
	
	public function config($configs)
	{
		if( ! is_array($configs) && is_string($configs) )
			$configs = array($configs);
		
		foreach($configs as $config)
		{
			$config_file = APPPATH . 'config' . DS . $config . '_config.php';
			
			if( ! file_exists($config_file) )
				show_error("Config file {$confg_file} dosn't exists");
			
			include_once($config_file);
		}
	}
	
	## ---------------------------------------------------------------
	
	public function autoloader()
	{
		if( ! file_exists(APPPATH . 'config/autoload.php') )
			show_error('autoload config not found!', __FILE__, __LINE__);
		
		include_once(APPPATH . 'config/autoload.php');
		
		if( ! isset($autoload) )
		{
			return FALSE;
		}
		
		foreach ( array('config' => 'config', 'helpers' => 'helper', 'libraries' => 'library') as $load => $method )
		{
			if( isset($autoload[$load]) )
			{
				$this->{$method}($autoload[$load]);
			}
		}
	}
	
	## ---------------------------------------------------------------
	
	private function _load_file()
	{
		
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file loader.php */
/* Location: ./system/core/loader.php */