<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

// Define the Limonata version
define('LIMONATA_VERSION', '0.0.1');

// Load the global functions
include_once( COREPATH . 'common.php' );

// load the input class
$INPUT = load_class('input', 'core', 'LIM_');

// load the router class
$ROUTER = load_class('router', 'core', 'LIM_');

// Load the base controller class
include_once( COREPATH . 'controller.php');

// @todo: URI & Router
$method				= ! empty($_GET['m']) ? $_GET['m'] : 'home';
$controller_path	=  APPPATH . 'controllers' . DS . $ROUTER->controller . '.php';

if( ! file_exists($controller_path) )
{
	exit('Controller file is not exists!' . $controller_path);
}

include_once( $controller_path );
$LIM = new $ROUTER->controller();
$LIM->{$ROUTER->method}();

/* End of file limonata.php */
/* Location: ./limonata.php */