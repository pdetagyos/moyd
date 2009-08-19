<?php

//
// User-related functions ------------------------------------------------------
//

class User {

	//
	// Return whether the given username is valid or not
	//
	public static function isValidUsername($name) {
		// Return true if the name contains only alphanumeric and underscore characters 
	   return eregi('[^a-z0-9_]', $name) ? FALSE : TRUE; 
	} 
	
	public static function generatePassword($password) {
		// Return an encrypted version of the given password
		return crypt($password, 'sImPl3');
	}
	
	public static function getUserInfo($username) {
		// Get the stored user information for the given username. If the user is found,
		// an associative array with the information is returned. If the user is not found,
		// null is returned
		
		$userInfo = null;
	
		// Load the user configuration file and parse it
		$userFile = Constants::$ROOT_DIR . 'config/users/'. $username . '.user';
	
		if (file_exists($userFile)) {
	
			$handle = @fopen($userFile, "r");
			if ($handle) {
				// Read the line describing the user values
				$userLine = fgets($handle);
	
				// Parse it out
				$userData = explode("|", $userLine);
	
				// Parse their data into the user info a-a
				$userInfo['username'] = $username;
				$userInfo['password'] = $userData[1];
				$userInfo['friendlyName'] = $userData[2];
				$userInfo['email'] = $userData[3];
				$userInfo['userPath'] = $userData[4];
				$userInfo['directoryMode'] = $userData[5];
				$userInfo['editorMode'] = $userData[6];
	
				fclose($handle);
			}			
		
		}
		else {
			// User does not exist
			$userInfo = "";
		}
	
		return $userInfo;
	}
	
	public static function saveUserInfo($username, $userInfo) {
	
		// Copy the user's info to a strictly-ordered array for persisting
		$userData = array(
			$userInfo['username'], 
			$userInfo['password'], 
			$userInfo['friendlyName'],
			$userInfo['email'], 
			$userInfo['userPath'], 
			$userInfo['directoryMode'],
			$userInfo['editorMode']
		);
	
		// Generate the proper file name
		$userFile = Constants::$ROOT_DIR . 'config/users/'. $username . '.user';
	
		// Assemble the user data line and write it to the user's config file
		$userLine = implode("|", $userData);
		try {
			file_put_contents($userFile, $userLine);
		}
		catch (Exception $e) {
			return false;
		}
				
		return true;
	}
	
	public static function addNewUser($username, $fullName) {
		// Add the new user's information to the file listing all users
		$ret = false;
		
		$userData = "\n" . $username . "|" . $fullName;
		
		try {
			$handle = @fopen(Constants::$ROOT_DIR . 'config/users.php', "a");
			if ($handle) {
				fwrite($handle, $userData);
				fclose($handle);
				$ret = true;
			}
		}
		catch (Exception $e) {
			$ret = false;
		}		
		
		return $ret;
	}
	
	public static function getUserLoginList() {
	// Return an array of Username/FriendlyName pairs for display in a login list
	
		$userList = array();
		
		// Load the user configuration file and parse it into an associative array
		$handle = @fopen(Constants::$ROOT_DIR . 'config/users.php', "r");
		if ($handle) {
			while (!feof($handle)) {
				// Read a line describing a user value
				$userLine = fgets($handle);
	
				if (strlen(trim($userLine)) > 0) {
					// Parse it out
					$userInfo = explode("|", $userLine);
	
					// Stick it in the user list array
					$userList[$userInfo[0]]= $userInfo[1];
				}
			}
			fclose($handle);
		}			
	
		return $userList;
	}
	
	public static function replacePassword($username, $newPassword) {
	
		// Generate the new password hash
		$newPWHash = User::generatePassword($newPassword);
	
		// Open the user's config file and parse it
		$userFile = Constants::$ROOT_DIR . 'config/users/'. $username . '.user';
		$handle = @fopen($userFile, "r");
		if ($handle) {
			// Read the line describing the user values
			$userLine = fgets($handle);
	
			// Parse it out
			$userData = explode("|", $userLine);
	
			fclose($handle);
		}			
	
		// Replace the old password hash with the new password hash
		$userData[1] = $newPWHash;
		
		// Also put the new password hash into the session data
		$_SESSION['password'] = $newPWHash;
	
		// Reassemble the user data line and write it to the user's config file
		$userLine = implode("|", $userData);
		file_put_contents($userFile, $userLine);
	
	}

}

?>