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
	UserNew - controller that handles Add User page 
*/

class UserNewController extends Controller {
	
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
		$this->renderView('UserNewView');		

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
			$this->createNewUser();
		}

	}
	

	// -- Private Methods --

	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Get data
		$uname = $this->pageState['username'];
		$pw = $this->pageState['password'];
		$fname = $this->pageState['fullname'];
		$email = $this->pageState['email'];
		$home = $this->pageState['homefolder'];
	
		// Do validation --
	
		// Validate the username
		if (strlen(trim($uname)) > 0) {
	
			// Make sure there are no illegal characters
			if (!User::isValidUsername($uname)) {
				$errors[] = 'Your proposed username contains characters that are not allowed. Please use letters and numbers only.';	
			}
			
			// Make sure the name doesn't already exist
			if (User::getUserInfo($uname)) {
				$errors[] = 'There is already a user with the username you propose. Please try using a different username.';
			}
	
			// Validate the other fields
			if (strlen(trim($pw)) == 0) $errors[] = 'Password cannot be blank.'; 
			if (strlen(trim($fname)) == 0) $errors[] = 'Full Name cannot be blank.'; 
			if (strlen(trim($email)) == 0) $errors[] = 'Email cannot be blank. ';
			if (strlen(trim($home)) == 0) $errors[] = 'Home Folder cannot be blank. For root access, use "/"';
	
		}
		else {
			// No username provided
			$errors[] = 'You must enter a username. Please use letters and numbers only.';
		}
	
		// Return the errors array
		return $errors;
	}

	private function createNewUser() {

		// Get data
		$uname = $this->pageState['username'];
		$pw = $this->pageState['password'];
		$fname = $this->pageState['fullname'];
		$email = $this->pageState['email'];
		$home = $this->pageState['homefolder'];
		$dm = $this->pageState['foldermgr'];
		$em = $this->pageState['adveditor'];
	
		// If the user is an admin, make their home folder blank
		if ($home == '/') $home = '';
	
		// Place the given data into the user's information
		$userInfo['username'] = $uname;
		$userInfo['password'] = User::generatePassword($pw);
		$userInfo['friendlyName'] = $fname;
		$userInfo['email'] = $email;
		$userInfo['userPath'] = $home;
		$userInfo['directoryMode'] = (strtolower($dm) == 'foldermgr') ? 'ADVANCED' : 'BASIC';
		$userInfo['editorMode'] = (strtolower($em) == 'adveditor') ? 'ADVANCED' : 'BASIC';
	
		// Save the information to the user's config file
		if (User::saveUserInfo($uname, $userInfo)) {
			// Now save the username and fullname to the user file
			if (User::addNewUser($uname, $fname)) {
				// Redirect to userMgmt page
				header('Location:/manage/userMgmt/showMessage/msg/newUserAdded/user/' . $uname);			
			}
			else {
				$errors[] = 'There was a problem adding the new user to the users file. Please check file permissions on the users file (make sure the file is writeable) and try again.';
				showForm($pageState, $errors);
			}
		}
		else {
			$errors[] = 'There was a problem creating a new user file for the user. Please check permissions on the config/users folder (make sure the folder is writeable) and try again.';
			$this->view->errors = $errors;
			$this->get();
		}
	
	}
	
}

?>
