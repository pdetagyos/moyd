<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

include_once(Constants::$MODEL_DIR . "user.php");


/**
	index - Controller for Index page.
*/

class IndexController extends Controller {

	// Public Action Handlers -----------------------------------------------------
	
	/**
		get - Default handler for HTTP GET of this page
	*/
	public function get() {
		// Get the list of users that can manage the site and sort by full name
		$userList = User::getUserLoginList();
		asort($userList);

		// Make the userlist accessible to the view
		$this->view->userList = $userList;

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('IndexView');		
	}

	/**
		post - Default handler for HTTP POST to this page
	*/
	public function post() {

		// Process the login
		if ($this->authenticateUser($_POST[username], $_POST[password])) {
			// Login succeeded - show the welcome page
			header("Location:/manage/welcome");
		}
		else {
			// Login failed - show error message
			$this->view->errors = array();
			$this->view->errors[] = 'Your password is incorrect. Please try again.';				
			$this->get();
		}
		
	}
	
	/**
		showMessage - Show the page with the desired message as designated by the URL params
	*/
	public function showMessage() {
		$this->view->messages = array();
		if ($this->pageState['msg'] == 'passwordReset') {
			$this->view->messages[] = 'Your password was reset successfully. Please check your email for your temporary password.';
		}
		$this->get();
	}


	// -- Private Methods ---------------------------------------------------------
	
	private function authenticateUser($username, $password) {
		
		// Look up the incoming user
		$currentUserInfo = User::getUserInfo($username);
		
		// Compare the incoming password to the stored password for the user
		if ($currentUserInfo['password'] == User::generatePassword($password)) {
			// The user has been authenticated, so start up a session and store user info there
			
			session_start();
			
			$_SESSION['auth'] = 'simple';
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $currentUserInfo['email'];
			$_SESSION['password'] = $currentUserInfo['password'];
			$_SESSION['userPath'] = trim($currentUserInfo['userPath']);
			$_SESSION['usernameFriendly'] = trim($currentUserInfo['friendlyName']);
			$_SESSION['directoryMode'] = trim($currentUserInfo['directoryMode']);
			$_SESSION['editorMode'] = trim($currentUserInfo['editorMode']);
			
			// Set the current working directory
			$_SESSION['cwd'] = $_SESSION['userPath'];
	
			return true;
		
		}
		else {
			// Authentication failed
			return false;
		}
	
	}
	
}

