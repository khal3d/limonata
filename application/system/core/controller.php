<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );
/**
 * $Rev$
 * $Date$
 * $HeadURL$
 */

class LIM_controller {
	
	public $load;
	public $library;
	public $model;
	
	private static $instance;
	
	public function __construct()
	{	
		foreach (loaded_classes() as $class => $class_name)
		{
			$this->$class =& load_class($class);
		}
		
		$this->load =& load_class('loader', 'core', 'LIM_');
		$this->load->autoloader();
		
		foreach ( $this->load->_libraries as $library) {
			// $this->library->$library = new $library();
			echo $library;
		}
	}
	
	## ---------------------------------------------------------------
	
	public static function &get_instance()
	{
		return self::$instance;
	}
	
	## ---------------------------------------------------------------
	
	public function limonata()
	{
		echo 'Limonata version: ' . LIMONATA_VERSION . ' Revision: ' . LIMONATA_REVISION;
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file controller.php */
/* Location: ./system/core/controller.php */