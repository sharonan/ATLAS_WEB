<?php

/** Check if environment is development and display errors **/

function setReporting() {
	if (DEVELOPMENT_ENVIRONMENT == true) {
		error_reporting(E_ALL ^ E_NOTICE);
		ini_set('display_errors','On');
	} else {
		error_reporting(E_ALL ^ E_NOTICE);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.'/error.log');
	}
}

/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
	if ( get_magic_quotes_gpc() ) {
		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/** Main Call Function **/

function callHook() {
	global $url;

	if(strpos($url, 'multi/')===0) $url = 'site/'.$url;
	if(strpos($url, 'free/')===0) $url = 'site/'.$url;
	if(strpos($url, 'pro/')===0) $url = 'site/'.$url;
	if(strpos($url, 'booked/')===0) $url = 'site/'.$url;
	if(strpos($url, 'respond/')===0) $url = 'site/'.$url;
	if(strpos($url, 'invited/')===0) $url = 'site/'.$url;
	if(strpos($url, 'verify/')===0) $url = 'site/'.$url;
// 	if(strpos($url, 'email/')===0) $url = 'site/'.$url;

	

	
	$urlArray = array();
	$urlArray = explode("/",$url);

	$controllers = array('site', 'user', 'ajax');
	$controller = $urlArray[0];
	
	array_shift($urlArray);
	$action = (isset($urlArray[0])?$urlArray[0]:'');
	
	array_shift($urlArray);
	$queryString = $urlArray;
	if(in_array(strtolower($controller), $controllers)){
		if(!file_exists(ROOT . '/app/controllers/' . ucfirst(strtolower($controller).'Controller.php'))){
			if($action == ''){
				$action = $controller;
				$controller = 'site';	

			} else {
				$controller = 'site';
				$action = 'homepage';
			}
		}
	} else {
		$queryString = array($controller);
		$controller = 'site';
		$action	= 'index';
	}
	$controllerName = $controller;
	$controller = ucwords($controller);
	$model = $controller . 'Model';
	$controller .= 'Controller';
	$dispatch = new $controller($model,$controller,$action,$queryString);

	if ((int)method_exists($controller, $action)) {
		call_user_func_array(array($dispatch,$action),$queryString);
	} else {
	/*	throw new Exception("
			Instance of $controller\n
			Controller name is $controllerName\n
			Model of $model\n
			Action of $action\n
			Query string of $queryString\n
			
		");
		/* Error Generation Code Here */
	}
}


/** Autoload any classes that are required **/
function __autoload($className) {
	if (file_exists(ROOT . '/app/lib/' . strtolower($className) . '.class.php')) {
		require_once(ROOT . '/app/lib/' . strtolower($className) . '.class.php');
	} else if (file_exists(ROOT . '/app/controllers/' . ucfirst($className) . '.php')) {
		require_once(ROOT . '/app/controllers/' . ucfirst($className) . '.php');
	} else if (file_exists(ROOT . '/app/models/' . ucfirst($className) . '.php')) {
		require_once(ROOT . '/app/models/' . ucfirst($className) . '.php');
	} else {
		echo "Trying to load  ". ucfirst($className) . '.php'.".\n";
		throw new Exception("Unable to load $className.");
	}
}

require_once (ROOT . '/app/lib/dao/BaseDAO.php');
require_once (ROOT . '/app/lib/Controller.php');
require_once (ROOT . '/app/lib/Model.php');
require_once (ROOT . '/app/lib/Template.php');
require_once (ROOT . '/app/lib/helpers.php');

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();