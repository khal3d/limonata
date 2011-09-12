<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

// Define the Limonata version
define('LIMONATA_VERSION', '0.0.1');

// Load the global functions
include_once( COREPATH . 'common.php' );

// Load the base controller class
include_once( COREPATH . 'controller.php');

// @todo: URI & Router
$controller			= ! empty($_GET['c']) ? $_GET['c'] : 'home';
$method				= ! empty($_GET['m']) ? $_GET['m'] : 'home';
$controller_path	=  APPPATH . 'controllers' . DS . $controller . '.php';

if( ! file_exists($controller_path) )
{
	exit('Controller file is not exists!');
}

include_once( $controller_path );
$LIM = new $controller();
$LIM->$method();

/* End of file limonata.php */
/* Location: ./limonata.php */