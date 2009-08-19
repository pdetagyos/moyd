<?php

/* 
 * Copyright (c) 2007-2008 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */



/**
	index - Default controller class 
*/

class Site404Controller extends Controller {

	// Controller actions -- 
	public function get() {
	
		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('Site404View');

	}
	
}

