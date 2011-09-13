<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

class Home extends LIM_controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	## ---------------------------------------------------------------
	
	public function home()
	{
		$this->load->view('home', array('name' => 'Khaled Attia'));
	}
	
	## ---------------------------------------------------------------
	
	public function phpinfo()
	{
		phpinfo();
	}
	
	## ---------------------------------------------------------------
	
}