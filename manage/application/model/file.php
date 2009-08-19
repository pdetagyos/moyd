<?php

// File-related functions ------------------------------------------------------

class File {
	//
	// Return file extension (the string after the last dot)
	//
	public static function fileExtension($file) {
		$a = explode(".", $file);
		$b = count($a);
		return $a[$b-1];
	}
	
	//
	// Return file name (the string before the first dot)
	//
	public static function fileName($file) {
		$a = explode(".", $file);
		return $a[0];
	}
	
	// 
	// Does the given string end with the other given string?
	//
	public static function endsWith( $str, $sub ) {
		return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
	}
	
	//
	// Determine if given string is a directory
	//
	public static function isDir($file) {
		$a = explode(".", $file);
		if (($file == '.') || ($file == '..') || (count($a) == 1)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	//
	// Get the current sandbox path
	//
	public static function getUserSandboxPath() {
		if (strlen($_SESSION['cwd']) > 0) {
			return Constants::$ROOT_DIR . 'sandbox' . $_SESSION['cwd'] . '/'; 
		}
		else {
			return Constants::$ROOT_DIR . 'sandbox/'; 
		}
	}
	
	//
	// Get the current publishing path
	//
	public static function getUserPublishingPath() {
		$dirParts = preg_split('/\//', Constants::$ROOT_DIR, -1, PREG_SPLIT_NO_EMPTY);
		$pubDir = "";
		for ($i = 0; $i <= count($dirParts) - 2; $i++) {
			$pubDir .= "/" . $dirParts[$i];
		}

		if (strlen($_SESSION['cwd']) > 0) {
			return $pubDir . '/' . substr($_SESSION['cwd'], 1) . '/'; 
		}
		else {
			return $pubDir . '/'; 
		}
	}
	
	//
	// Return whether the given filename is valid or not
	//
	public static function isValidFilename($name) {
		// Return true if the name contains only alphanumeric and underscore characters 
	   return eregi('[^a-z0-9_]', $name) ? FALSE : TRUE; 
	} 

}