<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class LIM_Router {
	
	protected $router;
	public $controller, $method = 'home';
	// public $method;
	
	## ---------------------------------------------------------------
	
	public function __construct()
	{
		$input =& load_class('input');
		$this->router = $input->get('router');
		$this->_set_routing($this->router);
	}
	
	## ---------------------------------------------------------------
	
	private function _set_routing($router)
	{
		if( $router == FALSE )
			return ;
		
		$router = explode('/', $router);
		
		$this->controller	= isset($router[0]) ? $router[0] : $this->controller;
		$this->method		= isset($router[1]) ? $router[1] : $this->method;
	}
	
	## ---------------------------------------------------------------
	
}

/* End of file router.php */
/* Location: ./system/core/router.php */