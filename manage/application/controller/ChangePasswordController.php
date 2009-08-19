<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");
include_once(Constants::$MODEL_DIR . "user.php");

/**
	ChangePassword - controller that handles Change Password page 
*/

class ChangePasswordController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('ChangePasswordView');		

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
			$this->changePassword();
		}

	}
	
	
	// -- Private Methods --
	
	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Do validation --

		// Verify the current password
		$currentPassword = $this->pageState['currentPassword'];
		if ($_SESSION['password'] != User::generatePassword($currentPassword)) {
			// Current password is incorrect
			$errors[] = 'The current password you provided is incorrect. Please enter your current password.';			
		}
	
		// Validate the new password
		$newPassword = $this->pageState['newPassword'];
		if (strlen($newPassword) == 0) {
			// No new password provided
			$errors[] = 'Your new password cannot be blank. Please enter a new password.';		
		}
	
		// Validate the retyped password
		$confirmPassword = $this->pageState['confirmPassword'];
		if (strlen($newPassword) == 0) {
			// No new password confirmation provided
			$errors[] = 'Your have not yet re-typed your new password to confirm it. Please do so.';		
		}
		
		if ($newPassword != $confirmPassword) {
			$errors[] = 'The value in the new password box does not match the re-typed confirmation value. Please enter the new password into both boxes again, so that we can ensure that your password is free of typos.';		
		}
	
		// Return the errors array
		return $errors;
	}
	
	
	function changePassword() {
	
		// Replace the current password in the user's config file
		User::replacePassword($_SESSION['username'], $this->pageState['newPassword']);
			
		// Navigate to the welcome page		
		header('Location:/manage/welcome/showMessage/msg/passwordChanged');			
	
	}

}

