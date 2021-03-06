<?php
/**
 * $Rev$
 * $Date$
 * $URL$
 */

// Display errors in production mode
ini_set('display_errors', 1);

/**
 * ---------------------------------------------------------------
 * Define the Limonata for security reasons
 * ---------------------------------------------------------------
 */
	define('LIMONATA', TRUE);

/**
 * ---------------------------------------------------------------
 * The name of this file, YES YOU CAN CHANGE THE NAME OF THIS FILE :)
 * ---------------------------------------------------------------
 */
	define('LIMONATA_INDEX', pathinfo(__FILE__, PATHINFO_BASENAME));

/**
 * ---------------------------------------------------------------
 * Short define for DIRECTORY_SEPARATOR
 * ---------------------------------------------------------------
 */
	define('DS', DIRECTORY_SEPARATOR);

/**
 * ---------------------------------------------------------------
 * Path to the system folder
 * ---------------------------------------------------------------
 */
	define('SYSPATH', str_replace(array('\\', '/'), DS, 'application/system') . DS);

/**
 * ---------------------------------------------------------------
 * Path to the application folder
 * ---------------------------------------------------------------
 */
	define('APPPATH', str_replace(array('\\', '/'), DS, 'application') . DS);

/**
 * ---------------------------------------------------------------
 * Path to the core folder
 * ---------------------------------------------------------------
 */
	define('COREPATH', SYSPATH . 'core' . DS);

/**
 * ---------------------------------------------------------------
 * Path to main folder
 * ---------------------------------------------------------------
 */
	define('LIMPATH', str_replace(LIMONATA_INDEX, '', __FILE__));

/**
 * ---------------------------------------------------------------
 * Define Limonata Prefix - Don't change it!
 * ---------------------------------------------------------------
 */
	define('LIM_PREFIX', 'LIM_');
/**
 * ---------------------------------------------------------------
 * Load the bootstrap file
 * ---------------------------------------------------------------
 */
	include_once( COREPATH . 'limonata.php' );

/* End of file index.php */
/* Location: ./index.php */