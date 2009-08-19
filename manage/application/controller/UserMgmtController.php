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
	UserMgmt - controller that handles User Management page 
*/

class UserMgmtController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Verify user is an administrator - site admins have the root dir as their userPath
		if ($_SESSION['userPath'] != '') {
			// Not an admin, so scram - don't even let on that this page exists!
			header("Location:/manage/welcome");
		}

		// Generate the user data and make it available to the view
		$userData = $this->getUserData();
		$this->view->userData = $userData;

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('UserMgmtView');		

	}
	
	public function showMessage() {

		if ($this->pageState['msg'] == 'passwordChanged') {
			$this->view->messages[] = 'Your password was changed successfully.';
		}
		else if ($this->pageState['msg'] == 'newUserAdded') {
			$this->view->messages[] = 'User: ' . $this->pageState['user'] . ' has been added successfully.';
		}
		else if ($this->pageState['msg'] == 'userDeleted') {
			$this->view->messages[] = 'User: ' . $this->pageState['user'] . ' has been deleted successfully.';
		}
		else if ($this->pageState['msg'] == 'userUpdated') {
			$this->view->messages[] = 'User: ' . $this->pageState['user'] . ' has been updated successfully.';
		}
		$this->get();
			
	}


	// -- Private Methods --

	private function getUserData() {
		$users = array();
		
		// Open up the users folder and loop through all the .user files
		foreach (scandir('config/users') as $file) { 
			if(File::fileExtension($file) == "user") {
				// Extract the user information from the file into a useful format
				$userInfo = User::getUserInfo(File::fileName($file));
				// Stuff the record into the result array
				$users[] = $userInfo;
			}
			
		}
		
		return $users;
	}
	
}

?>
