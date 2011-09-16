<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class LIM_Loader {
	
	private	$view_data	= array();
	public	$_views		= array();
	public	$_helpers	= array();
	public	$_model		= array();
	public	$_libraries	= array();
	public	$_config	= array();
	
	
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
			$this->_helpers[] = $helper;
		}
	}
	
	## ---------------------------------------------------------------
	
	public function model()
	{
		
	}
	
	## ---------------------------------------------------------------
	
	public function library($class)
	{
		if( is_array($class) )
		{
			foreach ( $class as $library )
			{
				$this->library($library);
			}
			
			return ;
		}
		
		$is_loaded = FALSE;
		
		$class_name = str_replace( array('_library.php'), '', trim($class, '/') );
		
		foreach ( array(SYSPATH .'libraries'. DS, APPPATH .'libraries'. DS) as $path )
		{
			$class_file = $class_name . '_library.php';
			if( file_exists($path . $class_file) )
			{
				include_once( $path . $class_file );
				if( file_exists( $path . 'MY_' . $class_file ) ) {
					include_once( $path . 'MY_' . $class_file );
				}
				// FIXME: not works
				$this->_libraries[$class_name] = array('path' => '');
				
				$is_loaded = TRUE;
				
				continue;
			}
		}
		
		if( ! $is_loaded ) {
			show_error('Unable to load the requested class: '. $class_name);
		}
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
		
		foreach ( array('config' => 'config', 'helpers' => 'helper', 'libraries' => 'library') as $type => $method )
		{
			if( isset($autoload[$type]) )
			{
				$this->{$method}($autoload[$type]);
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