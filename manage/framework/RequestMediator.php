<?php

/**
	RequestMediator - Singleton class that handles URL parsing and routing requests
		to the proper Controller/Action.
*/
class RequestMediator {

	// Members --------------------------------------------------------------------
	
	protected $_controller, $_action, $_params, $_body;
	
	protected $_time;

	static $_instance;		// Singleton instance
	
	// Public Interface -----------------------------------------------------------

	/**
	Get Singleton instance of this class
	*/	
	public static function getInstance() {
	
		if (! self::$_instance instanceof self) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}

	/**
	Route the current request to the proper Action within the proper Controller
	*/
	public function route() {
			
		if (class_exists($this->getController())) {
			$rc = new ReflectionClass($this->getController());
			if ($rc->implementsInterface('IController')) {
				if ($rc->hasMethod($this->getAction())) {
					$controller = $rc->newInstance();
					$method = $rc->getMethod($this->getAction());
					$method->invoke($controller);
				}
				else {
					throw new Exception("Action not found: " . $this->getAction() . 
						' on Controller: ' . $this->getController());
				}
			}
			else {
				throw new Exception("Desired Controller does not implement the IController interface: " . 
					$this->getController());
			}
		}	
		else {
			// Controller not found - have the 404 controller handle the request
			$controller = new site404Controller();
			$controller->get();
		}
	
	}

	/**
	Get Parameters passed via URL or form post
	*/
	public function getParams() {
		return $this->_params;
	}
	
	/**
	Get name of Controller that should handle the request
	*/
	public function getController() {
		return $this->_controller;
	}
	
	/**
	Get name of the Action that should handle the request
	*/
	public function getAction() {
		return $this->_action;
	}
	
	/**
	Get the HTML for the requested page
	*/
	public function getBody() {
		return $this->_body;
	}
	
	/**
	Set the HTML for the requested page
	*/
	public function setBody($body) {
		$this->_body = $body;
	}


	// Private Methods ------------------------------------------------------------

	/**
	This class is a Singleton, so the constructor is private.
	*/
	private function __construct() {
		$request = $_SERVER['REQUEST_URI'];
		
		$requestType = $_SERVER['REQUEST_METHOD'];

		// Parse out the URL - incoming URLs take the following form:
		// {domain}/manage/{controllerName}/{actionName}/params... (in name/value pairs)
		$request = str_replace('/manage/', '', $request);	// remove the manage dir from the URL 
		$urlParts = explode('/', trim($request, '/'));
		$this->_controller = (!empty($urlParts[0]) ? $urlParts[0] : 'Index')  . 'Controller'; // Default controller is IndexController
		$this->_action = !empty($urlParts[1]) ? $urlParts[1] : strtolower($requestType); // Default action is request type

		// Grab all parameters from the request
		$params = array();
		
		// If they exist, then grab the parameters from the URL
		if (!empty($urlParts[2])) {
			$keys = $values = array();
			for ($idx = 2, $cnt = count($urlParts); $idx < $cnt; $idx++) {
				if ($idx % 2 == 0) {
					// Even => key
					$keys[] = $urlParts[$idx];
				}
				else {
					// Odd => value
					$values[] = $urlParts[$idx];
				}
			}

			$params = array_combine($keys, $values);
		}

		// If POST, then grab the parameters from the form vars also
		if (strtolower($requestType) == 'post') {
			$params = array_merge($params, $_POST);
		}

		$this->_params = $params;
		
	}
	
}
