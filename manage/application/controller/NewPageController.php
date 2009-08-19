<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");
require_once(Constants::$MODEL_DIR . "file.php");
require_once(Constants::$MODEL_DIR . "config.php");

/**
	Welcome - controller that handles Main Control page 
*/

class NewPageController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Generate List of Page Types
		$this->view->pageTypes = $this->getPageTypes();

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('NewPageView');		

	}
	
	public function post() {

		Session::verifySession();

		// Validate the form and get errors
		if ($errors = $this->validateForm()) {
			// There were errors, so redisplay form with errors
			$this->view->errors = $errors;
			$this->get();
		}
		else {
			$this->createNewPage();
		}

	}
	
	
	// -- Private Methods --
	
	private function getPageTypes() {

		$listitems = array();
	
		// Add the Blank Page type
		$listitems []= 'Blank Page';
	
		// Add all available templates to the list		
		foreach(scandir(Constants::$ROOT_DIR . 'templates/sandbox') as $file) {
			if(File::fileExtension($file) == "html") {
				// Add the filename to the list
				$listitems[] = File::filename($file);
			}
		}
		
		return $listitems;

	}
	
	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Do validation --
	
		// Validate the file name
		$fileName = $this->pageState['newFilename'];
		if (strlen($fileName) > 0) {
			// Make sure there are no illegal characters
			if (!File::isValidFilename($fileName)) {
				$errors[] = 'Your proposed page name contains characters that are not allowed. Please use letters and numbers only.';	
			}
		}
		else {
			// No filename provided
			$errors[] = 'You must enter a page name. Please use letters and numbers only.';
		}
	
		// Return the errors array
		return $errors;
	}
	
	
	private function createNewPage() {

		// Create the page --
		$htmlBlock = '';
		
		// Process according to the current page mode
		if ($this->pageState['templateName'] == 'Blank Page') {
			// Blank page mode
	
			// Save to the Sandbox file
			file_put_contents(File::getUserSandboxPath() . $this->pageState['newFilename'] . '.html', $htmlBlock);
		
			// Navigate to the editor		
			header('Location:editPage/get/page/' . $this->pageState['newFilename']);
		}
		else {
			// Template mode
			
			// Determine if the chosen template needs further processing
			if (file_exists(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.config')) {
				// The template needs additional information -> navigate to the page for setting up new template pages 
				header('Location:newTemplatePage/get/templateName/' . $this->pageState['templateName'] . '/pageName/' . $this->pageState['newFilename']);
			}
			else {
				// Grab the template page data
				$htmlBlock = file_get_contents(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.html');
			
				// Save the template to the sandbox file
				file_put_contents(File::getUserSandboxPath() . $this->pageState['newFilename'] . '.html', $htmlBlock);
			
				// Navigate to the editor		
				header('Location:editPage/get/page/' . $this->pageState['newFilename']);			
			}
	
		}
	
	}

}

