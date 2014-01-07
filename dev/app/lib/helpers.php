<?php 
  /**
   * Put in header files
   * Any global and specific javascript is included.
   * Controller / view / global.js
   * Controller / view / view.js
   */
function headerJavascript(){
	global $url;
	if(strpos($url, 'multi')===0) $url = 'site/'.$url;
	if(strpos($url, 'booked')===0) $url = 'site/'.$url;
	if(strpos($url, 'respond')===0) $url = 'site/'.$url;
	if(strpos($url, 'invited')===0) $url = 'site/'.$url;
//  	if(strpos($url, 'email')===0) $url = 'site/'.$url;

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
				$action = '404';
			}
		}
	} else {
		$queryString = array($controller);
		$controller = 'site';
		$action	= 'index';
	}
	
	if (file_exists(ROOT . '/app/views/' . $controller . '/js/global.js')) {
		echo '<script type="text/javascript" src="'.PATH.'/app/views/' . $controller . '/js/global.js?v='.VERSION.'"></script>
		';
	} 
	if (file_exists(ROOT . '/app/views/' . $controller . '/js/' . $action.'.js')) {
		echo '<script type="text/javascript" src="'.PATH.'/app/views/' .$controller . '/js/' . $action . '.js?v='.VERSION.'"></script>
		';
	} 
}


function formatOffset($offset) {
       return sprintf('%+03d:%02u', floor($offset / 3600), floor(abs($offset) % 3600 / 60));
}