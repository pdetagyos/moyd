<?php

/* 
 * Copyright (c) 2007-2008 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

include_once(Constants::$MODEL_DIR . "user.php");
include_once(Constants::$MODEL_DIR . "config.php");


/**
	ForgotPassword - Controller for the Forgot Password page
*/

class ForgotPasswordController extends Controller {

	public function get() {

		// Get the list of users that can manage the site
		$userList = User::getUserLoginList();
		// Sort the list by full name
		asort($userList);

		// Make the userlist accessible to the view
		$this->view->userList = $userList;

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('ForgotPasswordView');		
	}
	
	public function post() {

		// Validate the form and get errors
		if ($errors = $this->validateForm()) {
			// There were errors, so redisplay form with errors
			$this->view->errors = $errors;
			$this->get();
		}
		else {
			$this->resetPassword();
		}

	}
	
	
	// -- Private Methods --
	
	private function validateForm() {
		// The errors array will hold all validation errors discovered
		$errors = array();
	
		// Do validation --
	
		// Validate the email
		$email = $this->pageState['email'];
		if (strlen($email) == 0) {
			// No email provided
			$errors[] = 'Your email address cannot be blank. Please enter you email address.';		
		}
	
		// Validate the email password
		$username = $this->pageState['username'];
	
		$currentUserInfo = User::getUserInfo($username);
		
		// Compare the incoming email to the stored email for the user
		if ($currentUserInfo['email'] != $email) {
			// Email addresses don't match
			$errors[] = 'The email address you provided does not match the one on file. Please enter the correct email address.';
		}
	
		// Return the errors array
		return $errors;
	}
	
	
	private function resetPassword() {

		// Generate a random temporary password
		srand(time());
		$random1 = rand(0, 10000);
		$random2 = rand(500, 1000);
		$newPassword = $random1 . $random2;
		
		// Replace the current password in the user's config file
		User::replacePassword($this->pageState['username'], $newPassword);
			
		$msg = "From Website: " . Config::siteConfig("SITE_NAME") . " at " . Config::siteConfig("SITE_URL") . "\n\n";
		$msg .= "Your password has been reset. Your new password is:\n\n" . $newPassword . "\n\n";
		$msg .= "You can log into your account using this temporary password and then change your password to something else by clicking on the 'Change Your Password' link on the Welcome page.\n\n";	
			
		// Send an email to the user with their new password
		$this->sendEmail($this->pageState['email'], 'Your ' . Config::siteConfig("SITE_NAME") . ' password has been reset.', $msg);	
			
		header("Location:/manage/index/showMessage/msg/passwordReset");
		
	}
	
	private function sendEmail($to, $subject, $message) {
		// Build email 
		$header = "From: SiteCrafter @ " . Config::siteConfig("SITE_URL") . "\n";
	
		// Send email
		if(@mail($to, $subject, $message, $header) == false) {
			$errors .= "There was an error sending email.";
		}
	}
	
	
}

