<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");
require_once(Constants::$MODEL_DIR . "file.php");
require_once(Constants::$MODEL_DIR . "user.php");

/**
	UserDelete - controller that handles Delete User page 
*/

class UserDeleteController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Verify user is an administrator - site admins have the root dir as their userPath
		if ($_SESSION['userPath'] != '') {
			// Not an admin, so scram - don't even let on that this page exists!
			header("Location:/manage/welcome");
		}

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('UserDeleteView');		

	}

	public function post() {

		Session::verifySession();

		// Verify user is an administrator - site admins have the root dir as their userPath
		if ($_SESSION['userPath'] != '') {
			// Not an admin, so scram - don't even let on that this page exists!
			header("Location:/manage/welcome");
		}

		// Validate the form and get errors
		if ($errors = $this->validateForm()) {
			// There were errors, so redisplay form with errors
			$this->view->errors = $errors;
			$this->get();
		}
		else {
			$this->deleteUser();
		}

	}
	

	// -- Private Methods --

	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// No validation for this page
	
		// Return the errors array
		return $errors;
	}

	private function deleteUser() {

		// Get data
		$username = $this->pageState['userToDelete'];
		$conf = $this->pageState['confirm'];
	
		// Make sure that the user confirmed the deletion
		if ((strtolower($conf) == 'yes') || (strtolower($conf) == '"yes"')) {
			// Remove the user's config file
			$userFile = Constants::$ROOT_DIR . 'config/users/'. $username . '.user';
			unlink($userFile);
			
			// Remove the user from the login list
			$loginList = User::getUserLoginList();
			unset($loginList[$username]);
			$updatedLL = '';
			foreach($loginList as $uname => $fname) {
				$updatedLL .= $uname . '|' . $fname . "\n";
			}
			$userFile = Constants::$ROOT_DIR . 'config/users.php';
			file_put_contents($userFile, $updatedLL);
			
			// Redirect to the User Mgmt page with message
			header('Location:/manage/userMgmt/showMessage/msg/userDeleted/user/' . $username);					
		}
		else {
			// Redirect to User Mgmt page
			header("Location:/manage/userMgmt");
		}
	
	}
	
}

?>
