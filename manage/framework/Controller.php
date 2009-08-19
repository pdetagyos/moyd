<?php

/**
	Controller Interface and base class.  All controllers derive from this class.
*/

interface IController {}


class Controller implements IController {

	private $_fc;
	
	protected $pageState, $view;

	function __construct() {
		// Get the parameters from the request 
		$this->fc = RequestMediator::getInstance();
		$this->pageState = $this->fc->getParams();

		// Start/Continue a session
		session_start();

		// Instantiate a view to be used by the controller
		$this->view = new View();
		$this->view->session = $_SESSION;
		$this->view->pageState = $this->pageState;
	}
	
	function renderView($viewName) {
		$result = $this->view->render($viewName);		
		$this->fc->setBody($result);		
	}
	
}