<?php

// Require Framework components --

require_once('framework/Constants.php');
require_once('framework/Form.php');
require_once('framework/RequestMediator.php');
require_once('framework/Controller.php');
require_once('framework/View.php');


// Require the error controller classes

require_once(Constants::$CONTROLLER_DIR . 'site404Controller.php');


// Allow auto-loading of Controller classes on first use

function __autoload($class) {
	$controllerFile = Constants::$CONTROLLER_DIR . $class . '.php';
	if (file_exists($controllerFile)) {
		require_once($controllerFile);
	}
	else {
		// Controller not found, so show 404 page.
		$controller = new Site404Controller();
		$controller->get();
	}
}

function exceptionHandler($ex) {
	$msg = '<h1>Exception not handled - caught by global handler</h1>';
	$msg .= '<strong>Message</strong>: <em>' . $ex->getMessage() . '</em><br/>';
	$msg .= '<strong>in</strong> ' . $ex->getFile() . ' <strong>Line</strong>: ' . $ex->getLine() . '<br/>';
	$msg .= '<strong>trace</strong>: <br/>';
	$i = 1;
	foreach ($ex->getTrace() as $trace) {
		$msg .= "[$i]";
		$msg .= ' ==> ' . $trace['file'] . ' Line: ' . $trace['line'] .'<br/>';
		$msg .= ' ======> ' . $trace['class'] . '::' . $trace['function'] . ' Args: ' . print_r($trace['args'], true) . '<br/>';
		$i++;
	}

//	print_r($ex->getTrace());	
	// For now, just print the message to the screen. For production, show a generic error screen and 
	// log the details instead.
	print $msg;
	// TODO - send to framework error page
}


// Setup error reporting and handling of top-level exceptions

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_exception_handler('exceptionHandler');

// Initialize the FrontController and route the request

$front = RequestMediator::getInstance();
$front->route();

print $front->getBody();



