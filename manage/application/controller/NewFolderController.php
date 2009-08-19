<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");
require_once(Constants::$MODEL_DIR . "file.php");

/**
	NewFolderController - controller for new folder page 
*/

class NewFolderController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// If user does not have advanced folder rights, send them away
		if ($_SESSION['directoryMode'] != 'ADVANCED') {
			header('Location:/manage/welcome');
		}
		
		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('NewFolderView');		

	}
	
	public function post() {

		Session::verifySession();

		// If user does not have advanced folder rights, send them away
		if ($_SESSION['directoryMode'] != 'ADVANCED') {
			header('Location:/manage/welcome');
		}

		// Validate the form and get errors
		if ($errors = $this->validateForm()) {
			// There were errors, so redisplay form with errors
			$this->view->errors = $errors;
			$this->get();
		}
		else {
			$this->createNewFolder();
		}

	}
	
	
	// -- Private Methods --
	
	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Do validation --
	
		// Validate the file name
		$folderName = $this->pageState['newFolderName'];
		if (strlen($folderName) > 0) {
			// Make sure there are no illegal characters
			if (!File::isValidFilename($folderName)) {
				$errors[] = 'Your proposed folder name contains characters that are not allowed. Please use letters and numbers only.';	
			}
		}
		else {
			// No folder name provided
			$errors[] = 'You must enter a folder name. Please use letters and numbers only.';
		}
	
		// Return the errors array
		return $errors;
	}
	
	
	private function createNewFolder() {

		try {
			// Create the folder in both the sandbox AND the publishing locations --
			mkdir(File::getUserSandboxPath() . $this->pageState['newFolderName'], 0775);
			mkdir(File::getUserPublishingPath() . $this->pageState['newFolderName'], 0775);

			// Navigate to the welcome page		
			header('Location:/manage/welcome');			
		}
		catch (Exception $e) {
			$errors[] = 'Cannot create folder. Do you already have a folder with this name?';
			$this->view->errors = $errors;
			$this->get();
		}
		
	
	}

}

