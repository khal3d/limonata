<?php if ( ! defined('LIMONATA') ) exit( 'No direct script access allowed' );

if ( ! function_exists('load_class'))
{
	function &load_class($class, $directory = 'libraries', $prefix = 'LIM_')
	{
		static $_classes;
		if( isset($_classes[$class]) )
		{
			return $_classes[$class];
		}
		
		$class_name = FALSE;
		
		foreach ( array(SYSPATH, APPPATH) as $path )
		{
			$class_file_path = $path . $directory . DS . $class . '.php';
			if( file_exists($class_file_path) )
			{
				$class_name = $prefix . $class;
				if( ! class_exists($class_name) )
				{
					include_once($class_file_path);
				}
				
				break;
			}
			
			if( file_exists(APPPATH . $directory . DS . 'MY_' . $class . '.php') )
			{
				$class_name = 'MY_' . $class;
				if( ! class_exists($class_name) )
				{
					include_once(APPPATH . $directory . DS . $className . '.php');
				}
			}
		}
		
		// If class not find .. exit!
		if ($class_name === FALSE)
		{
			// Script will exit.
			exit('Could not find class file '. $class .'.php');
		}
		
		loaded_classes($class, $class_name);
		
		$_classes[$class] = new $class_name();
		return $_classes[$class];	
	}
}

## ---------------------------------------------------------------

if( ! function_exists( 'loaded_classes' ) )
{
	function loaded_classes($class = '', $className = '')
	{
		static $_classes = array();
		
		if ( ! empty($class) )
		{
			$_classes[strtolower($class)] = $className;
		}
		
		return $_classes;
	}
}

## ---------------------------------------------------------------

if( ! function_exists('show_error') )
{
	function show_error($message, $file = '', $line = '')
	{
		//@todo: Exceptions class
		exit($message . "<br />{$file}:{$line}");
	}
}

## ---------------------------------------------------------------