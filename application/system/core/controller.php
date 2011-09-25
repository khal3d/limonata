<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );
/**
 * $Rev$
 * $Date$
 * $URL$
 */

class LIM_controller {
	
	public $load;
	public $library, $model;
	
	private static $instance;
	
	public function __construct()
	{
		self::$instance =& $this;
		
		$this->library	= new stdClass;
		$this->model	= new stdClass;
		
		foreach ( loaded_classes() as $class => $class_name )
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
		echo 'Limonata version: ' . LIMONATA_VERSION . ' Revision: ' . SVN_REVISION;
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file controller.php */
/* Location: ./system/core/controller.php */