<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");

/**
	Logout - controller that handles Logout page 
*/

class LogoutController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Completely clear and remove session
		session_unset();
		session_destroy();

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('LogoutView');		

	}
	
}

?>
