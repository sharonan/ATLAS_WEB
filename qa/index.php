<?php	
session_start();

define('DEVELOPMENT_ENVIRONMENT', false);
define('DS', DIRECTORY_SEPARATOR);

	// If files are separated by folder from the document root
	// define your development directory here, otherwise leave it blank
	// Examples:
	// define('DIRECTORY', 'development/sitefolder2');
	// define('DIRECTORY', 'live');
	// define('DIRECTORY', '');
	// (Don't append or prepend directory separators, but include any inbetween)

if(DEVELOPMENT_ENVIRONMENT == true)	
	//DEVELOPMENT
	define('DIRECTORY', '');
else
	//LIVE
	define('DIRECTORY', 'qa');



if(DIRECTORY != '')
	define('ROOT', dirname(dirname(__FILE__)) . '/' . DIRECTORY);
else 
	define('ROOT', dirname(dirname(__FILE__)) );

	
if($_SERVER['REQUEST_URI'])
$url = substr( $_SERVER['REQUEST_URI'], 1 );
 else
$url = 'site/index';
 
require_once (ROOT . '/app/config/config.inc.php');
require_once (ROOT . '/app/config/base.inc.php');
