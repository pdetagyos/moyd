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
	UserEdit - controller that handles Edit User page 
*/

class UserEditController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Verify user is an administrator - site admins have the root dir as their userPath
		if ($_SESSION['userPath'] != '') {
			// Not an admin, so scram - don't even let on that this page exists!
			header("Location:/manage/welcome");
		}

		// Load up the user's information and make it available to the view
		if ($this->pageState['user']) {
			$userInfo = User::getUserInfo($this->pageState['user']);
			$this->view->pageState['_username'] = $userInfo['username'];
			$this->view->pageState['password'] = $userInfo['password'];
			$this->view->pageState['fullname'] = $userInfo['friendlyName'];
			$this->view->pageState['email'] = $userInfo['email'];
			$this->view->pageState['homefolder'] = $userInfo['userPath'];
			$this->view->pageState['foldermgr'] = ($userInfo['directoryMode'] == 'ADVANCED') ? 'foldermgr' : '';
			$this->view->pageState['adveditor'] = ($userInfo['editorMode'] == 'ADVANCED') ? 'adveditor' : '';
		}

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('UserEditView');		

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
			$this->updateUser();
		}

	}
	

	// -- Private Methods --

	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Get data
		$uname = $this->pageState['_username'];
		$fname = $this->pageState['fullname'];
		$email = $this->pageState['email'];
		$home = $this->pageState['homefolder'];
		$dm = $this->pageState['foldermgr'];
		$em = $this->pageState['adveditor'];
	
		// Do validation --
		if (strlen(trim($uname)) == 0) $errors[] = 'Username cannot be blank.'; 
		if (strlen(trim($fname)) == 0) $errors[] = 'Full Name cannot be blank.'; 
		if (strlen(trim($email)) == 0) $errors[] = 'Email cannot be blank. ';
		if (strlen(trim($home)) == 0) $errors[] = 'Home Folder cannot be blank. For site admins, use "/"';
	
		// Return the errors array
		return $errors;
	}

	private function updateUser() {

		// Get data
		$uname = $this->pageState['_username'];
		$fname = $this->pageState['fullname'];
		$email = $this->pageState['email'];
		$home = $this->pageState['homefolder'];
		$dm = $this->pageState['foldermgr'];
		$em = $this->pageState['adveditor'];
	
		// If the user is an admin, make their home folder blank
		if ($home == '/') $home = '';
	
		try {
			// Process the incoming user data
			$userInfo = User::getUserInfo($uname);
	
			// Place the given data into the user's information
			$userInfo['friendlyName'] = $fname;
			$userInfo['email'] = $email;
			$userInfo['userPath'] = $home;
			$userInfo['directoryMode'] = (strtolower($dm) == 'foldermgr') ? 'ADVANCED' : 'BASIC';
			$userInfo['editorMode'] = (strtolower($em) == 'adveditor') ? 'ADVANCED' : 'BASIC';
	
			// Save the information to the user's config file
			User::saveUserInfo($uname, $userInfo);
		
			// Head back to the user management page
			header('Location:/manage/userMgmt/showMessage/msg/userUpdated/user/' . $uname);
		}
		catch (Exception $exc) {
			$errors[] = 'There was a problem saving the user file for the user. Please check permissions on the config/users directory and the specific user configuration file (make sure both are writeable) and try again.';
			$this->view->errors = $errors;
			$this->get();
		}
	
	}
	
}

?>
