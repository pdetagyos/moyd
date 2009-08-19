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

class NewTemplatePageController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Make sure the template config file exists
		if (!file_exists(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.config')) {
			$this->view->errors = array('Could not find template: ' . $this->pageState['templateName']);
		}

		// Load information about the template fields
		$this->view->templateFields = $this->getTemplateFields();

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('NewTemplatePageView');		

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
			$this->createNewTemplatePage();
		}

	}
	
	
	// -- Private Methods --

	private function getTemplateFields() {

		$templateFields = array();
			
		// Open the config file for the current template
		$handle = @fopen(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.config', "r");
		if ($handle) {
			while (!feof($handle)) {
				// Read a line describing a template field
				$templateLine = fgets($handle);

				// Parse it out
				$fieldDefinition = explode("|", $templateLine);
	
				$f = array();
				$f['Name'] = $fieldDefinition[0];
				$f['Question'] = $fieldDefinition[1];
				$f['Type'] = $fieldDefinition[2];
				$f['Data'] = $fieldDefinition[3];

				$templateFields []= $f;	
			}
			fclose($handle);
		}	
		
		return $templateFields;
	}
	
	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Do validation --
	
		// Validate the file name
		$fileName = $this->pageState['pageName'];
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
	
	
	private function createNewTemplatePage() {

		// Load up the template 
		$templateHTML = file_get_contents(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.html');
		
		// Open the config file for the current template
		$handle = @fopen(Constants::$ROOT_DIR . 'templates/sandbox/' . $this->pageState['templateName'] . '.config', "r");
		if ($handle) {
			// Loop through the fields, replacing the tokens in the template with the user-provided data
			while (!feof($handle)) {
				// Read a line describing a template field
				$templateLine = fgets($handle);
		
				// Parse it out
				$fieldDefinition = explode("|", $templateLine);
		
				// Generate HTML to request that information
				$fieldName = $fieldDefinition[0];
				
				// Replace the token with the user data
				$templateHTML = str_replace("[[" . $fieldName . "]]", $this->pageState[$fieldName], $templateHTML);
			}
			
			// Replace special "reserved" template tokens
			$templateHTML = str_replace("[[CURRENT_PUBLISH_PATH]]", File::getUserPublishingPath(), $templateHTML);
			
			fclose($handle);
		}	
		
		// Save the generated page
		file_put_contents(File::getUserSandboxPath() . $this->pageState['pageName'] . '.html', $templateHTML);
		
		// Send the user to the editor page to edit the new page
		header("Location:/manage/editPage/get/page/" . $this->pageState['pageName']);
	
	}

}

