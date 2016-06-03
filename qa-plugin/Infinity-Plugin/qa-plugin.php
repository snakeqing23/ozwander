<?php

/*
	Plugin Name: Infinity Plugin
	Plugin URI: 
	Plugin Description: Framework for Infinity theme
	Plugin Version: 
	Plugin Date: 
	Plugin Author:
	Plugin License: 
	Plugin Minimum Question2Answer Version: 1.6
	Plugin Update Check URI: 
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}

define('IT_DIR', dirname( __FILE__ ));
define('IT_URL', it_get_base_url().'/qa-plugin/Infinity-Plugin');
define('IT_THEME_URL', it_get_base_url().'/qa-theme/Infinity-Theme');
define('IT_THEME_DIR', QA_THEME_DIR . '/Infinity-Theme');
define('IT_VERSION', 1);

require_once(IT_DIR. '/functions.php');

// register plugin language
qa_register_plugin_phrases('language/it-lang-*.php', 'Infinity');

qa_register_plugin_module('page', 'options.php', 'it_options', 'Infinity Options');

qa_register_plugin_overrides('overrides.php');

qa_register_plugin_layer('it-layer.php', 'Infinity Plugin Layer');

qa_register_plugin_module('event', 'it-event.php', 'it_event', 'Infinity Plugin Event');

qa_register_plugin_module('process', 'it-process.php', 'it_process', 'Infinity Plugin Process');



function it_get_base_url()
{
	/* First we need to get the protocol the website is using */
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https://' : 'http://';

	/* returns /myproject/index.php */
	if(QA_URL_FORMAT_NEAT == 0 || strpos($_SERVER['PHP_SELF'],'/index.php/') !== false):
		$path = strstr($_SERVER['PHP_SELF'], '/index', true);
		$directory = $path;
	else:
		$path = $_SERVER['PHP_SELF'];
		$path_parts = pathinfo($path);
		$directory = $path_parts['dirname'];
		$directory = ($directory == "/") ? "" : $directory;
	endif;       
		
		$directory = ($directory == "\\") ? "" : $directory;
		
	/* Returns localhost OR mysite.com */
	$host = $_SERVER['HTTP_HOST'];

	return $protocol . $host . $directory;
}