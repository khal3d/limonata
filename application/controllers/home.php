<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );
/**
 * $Rev$
 * $Date$
 * $URL$
 */

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
	
	public function gettext()
	{
		$this->load->library('test_library.php');
		printr($this->load);
		echo $this->library->test->my_test_method('Hello, World!');
	}
	
	## ---------------------------------------------------------------
	
}