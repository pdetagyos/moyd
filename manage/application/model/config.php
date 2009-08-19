<?php

// Configuration-related functionality -----------------------------------------

class Config {

	//
	// Retrieve the site configuration value for the given setting
	//
	public static function siteConfig($settingName) {
	
		if (isset($_SESSION['siteConfig'])) {
			// We already have site information cached, so return the desired value
			return $_SESSION['siteConfig'][$settingName];
		}
		else {
			// We haven't yet loaded up the site configuration file
			
			// Load the site configuration file and parse it into an associative array
			$handle = @fopen(Constants::$ROOT_DIR . 'config/siteConfig.php', "r");
			if ($handle) {
				while (!feof($handle)) {
					// Read a line describing a config value
					$configLine = fgets($handle);
		
					// Parse it out
					$configDefinition = explode("|", $configLine);
		
					// Stick it in the config associative array
					$siteConfig[trim($configDefinition[0])] = trim($configDefinition[1]);
				}
				fclose($handle);
			}			
	
			// See if we can cache the config info
			if (isset($_SESSION['username'])) {		
				// Yes - we have a session, so save the configuration information to the session, so we don't have to load it again
				$_SESSION['siteConfig'] = $siteConfig;
			}
			
			// Return the desired value
			return $siteConfig[$settingName];
		}
	}

}

?>