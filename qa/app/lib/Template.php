<?php

class Template {

	protected $variables = array();
	protected $_controller;
	protected $_action;

	function __construct($controller,$action) {
		$this->_controller = $controller;
		$this->_action = $action;
	}

	/** Set Variables **/

	function set($name,$value) {
		$this->variables[$name] = $value;
	}

	/** Display Template **/

    function render() {
		global $set_template, $set_view;
		if(!$set_view) $set_view = strtolower($this->_action);
		extract($this->variables);
		if($error) die($error);
		
		if(($set_template != '') && (file_exists(ROOT . "/app/views/$set_template/header.php"))){
			include (ROOT . "/app/views/$set_template/header.php");
		} elseif (file_exists(ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/header.php')) {
			include (ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/header.php');
		} else {
			include (ROOT . '/app/views/site/header.php');
		}

		if (file_exists(ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/' . $set_view . '.php')) {
			include (ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/' . $set_view . '.php');
		} else {
		//Here we check to see if it was an ajax call, and return the empty ajax views.
			if(substr(strtolower($this->_controller), 0, -10) == 'ajax'){
				include (ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/index.php');
			} elseif(($set_template != '') && (file_exists(ROOT . "/app/views/$set_template/index.php"))){
				include (ROOT . "/app/views/$set_template/header.php");
			} else {
				//If no file is found, display custom error page
				include (ROOT . '/app/views/site/homepage.php');
			}
		}

		if(($set_template != '') && (file_exists(ROOT . "/app/views/$set_template/footer.php"))){
			include (ROOT . "/app/views/$set_template/footer.php");
		} elseif (file_exists(ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/footer.php')) {
			include (ROOT . '/app/views/' . substr(strtolower($this->_controller), 0, -10) . '/footer.php');
		} else {
			include (ROOT . '/app/views/site/footer.php');
		}
    }

}