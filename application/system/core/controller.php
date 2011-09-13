<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class LIM_controller {
	
	public $load;
	private static $instance;
	
	public function __construct()
	{	
		foreach (loaded_classes() as $class => $class_name)
		{
			$this->$class =& load_class($class);
		}
		
		$this->load =& load_class('loader', 'core', 'LIM_');
		$this->load->autoloader();
	}
	
	## ---------------------------------------------------------------
	
	public static function &get_instance()
	{
		return self::$instance;
	}
	
	## ---------------------------------------------------------------
	
	public function limonata()
	{
		echo 'Limonata version: ' . LIMONATA_VERSION;
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file controller.php */
/* Location: ./system/core/controller.php */