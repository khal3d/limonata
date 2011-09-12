<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class LIM_controller {
	
	public $load;
	
	public function __construct()
	{	
		foreach (loaded_classes() as $class => $class_name)
		{
			$this->$class =& load_class($class);
		}
		
		$this->load =& load_class('loader', 'core', 'LIM_');
	}
	
	## ---------------------------------------------------------------
	
	public function hello_world()
	{
		echo 'Hello, World!';
	}
	
	## ---------------------------------------------------------------
	
}