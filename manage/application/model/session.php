<?php

// Session-related functions --

class Session {

	public static function verifySession() {
		if ($_SESSION['auth'] != 'simple') {
			header("Location:/manage/index");	
		}
	}
	
	public static function isVerified() {
		if ($_SESSION['auth'] == 'simple') {
            return true;
        }
        else {
            return false;
        }	    
	}

}