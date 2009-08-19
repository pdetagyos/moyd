<?php

/* 
 * Copyright (c) 2007-2009 Peter C. de Tagyos. All rights reserved.

 * This software is provided "as is," without warranty of any kind, 
 * express or implied. In no event shall the author or contributors be held 
 * liable for any damages arising in any way from the use of this software.
 */

require_once(Constants::$MODEL_DIR . "session.php");
require_once(Constants::$MODEL_DIR . "file.php");
require_once(Constants::$MODEL_DIR . "config.php");

/**
	Welcome - controller that handles Main Control page 
*/

class WelcomeController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// Generate List of Folders
		$this->view->folders = $this->getFolders();

		// Generate list of Published files
		$this->view->published = $this->getPublished();
		
		// Generate list of Unpublished files
		$this->view->unpublished = $this->getUnpublished();

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('WelcomeView');		

	}
	
	public function showMessage() {

		if ($this->pageState['msg'] == 'passwordChanged') {
			$this->view->messages[] = 'Your password was changed successfully.';
		}
		
		$this->get();
			
	}

	public function navTo() {

		Session::verifySession();

		// Make sure we're an advanced user
		if ($_SESSION['directoryMode'] != 'ADVANCED') {
			header("Location:/manage/welcome");
		}
		
		// Change the current working directory based on the parameter passed in
		if ($this->pageState['folder'] == 'UP') {
			// Move to the parent folder if we're not already at user's root path
			if ($_SESSION['cwd'] != $_SESSION['userPath']) {
				$pathBits = explode('/', $_SESSION['cwd']);
				unset($pathBits[count($pathBits)-1]);
				$_SESSION['cwd'] = implode('/', $pathBits);	
			}
		}
		else {
			// Move to the named child folder
			$_SESSION['cwd'] .= '/' . $this->pageState['folder'];	
		}

		// Send the user to the welcome page
		header("Location:/manage/welcome");
		
	}
	
	public function previewPage() {

		Session::verifySession();
		
		// Load up the page template data
		$templatePageData = file_get_contents(Constants::$ROOT_DIR . 'templates/site/pageTemplate.html');
		
		// Load up the data for the page we're publishing
		$currentPageName = $this->pageState['page'];
		$fileData = '';
		if ($currentPageName != '') {
			$fileData = file_get_contents(File::getUserSandboxPath() . $currentPageName . '.html');
		}
		
		// Place the data in the template and send it to the browser
		$templatePageData = str_replace('[[PAGE_TITLE]]', 'Previewing Page: ' . $currentPageName, $templatePageData);
		$publishedPageContent = str_replace('[[PAGE_CONTENT]]', $fileData, $templatePageData);
		
		echo $publishedPageContent;

	}
	
	public function publishPage() {

		Session::verifySession();

		// Load up the page template data
		$templatePageData = file_get_contents(Constants::$ROOT_DIR . 'templates/site/pageTemplate.html');
		
		// Load up the data for the page we're publishing
		$currentPageName = $this->pageState['page'];
		$fileData = '';
		if ($currentPageName != '') {
			$fileData = file_get_contents(File::getUserSandboxPath() . $currentPageName . '.html');
		}

		// Load up page metadata (if it exists)
		// Load up existing page metadata information (if it exists)
		$pageTitle = '';
		$pageDescription = '';
		$pageKeywords = '';
		$handle = @fopen(File::getUserSandboxPath() . $currentPageName . '.config', "r");
		if ($handle) {
			// Read the title
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageTitle = trim($a[1]);	}
			// Read the description
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageDescription = trim($a[1]);		}
			// Read the keywords
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageKeywords = trim($a[1]);	}
			fclose($handle);
		}
		
		// Place the data in the template and write the new file to the live site
		$templatePageData = str_replace('[[PAGE_TITLE]]', $pageTitle, $templatePageData);
		$templatePageData = str_replace('[[PAGE_DESCRIPTION]]', $pageDescription, $templatePageData);
		$templatePageData = str_replace('[[PAGE_KEYWORDS]]', $pageKeywords, $templatePageData);
		$publishedPageContent = str_replace('[[PAGE_CONTENT]]', $fileData, $templatePageData);
		file_put_contents(File::getUserPublishingPath() . $currentPageName . '.html', $publishedPageContent);
		
		// Rename the sandbox file so it doesn't appear on the Welcome page
		rename(File::getUserSandboxPath() . $currentPageName . '.html', File::getUserSandboxPath() . $currentPageName . '.published');
		
		// Then return to the Welcome page
		header("Location:/manage/welcome");

	}

	public function unpublishPage() {

		Session::verifySession();

		// Remove the page from the live site
		$currentPageName = $this->pageState['page'];
		unlink(File::getUserPublishingPath() . $currentPageName . '.html');
		
		// Rename the sandbox file so it reappears on the Welcome page
		rename(File::getUserSandboxPath() . $currentPageName . '.published', File::getUserSandboxPath() . $currentPageName . '.html');
		
		// Then return to the Welcome page
		header("Location:/manage/welcome");

	}
	
	public function deletePage() {

		Session::verifySession();

		// Remove the page & its meta file
		$currentPageName = $this->pageState['page'];
		unlink(File::getUserSandboxPath() . $currentPageName . '.html');
		unlink(File::getUserSandboxPath() . $currentPageName . '.config');
		
		// Then return to the Welcome page
		header("Location:/manage/welcome");
	
	}


	// -- Private Methods --

	// Get all the folders visible to the user from the current folder  
	private function getFolders() {

	    $folders = array();

		// If we're running in advanced directory mode, get information about the folders
		if ($_SESSION['directoryMode'] == 'ADVANCED') {

		    $path = File::getUserSandboxPath();

			// If we're not at the user's top-level folder, show the parent folder
			if ($_SESSION['cwd'] != $_SESSION['userPath']) {
				$folders[] = '..';
			}

			foreach (scandir($path) as $file) { 
				if (File::isDir($file) && ($file != '.') && ($file != '..')) {
					$folders[] = $file;
				}
			}
			
		}		

		return $folders;
	
	}	
	
	// Get all the files and folders in the published folder  
	private function getPublished() {

	    $path = File::getUserPublishingPath();
	    $published = array();

		// Loop through all the .html files in the published folder  
		foreach (scandir($path) as $file) { 
			// We only want to show the files that the user can edit (html files)
			if(File::fileExtension($file) == "html") {
				$published[] = array("name" => urlencode(File::fileName($file)), "path"=> $_SESSION['cwd'] . '/' . $file);
			}
		}

		return $published;

	}

	private function getUnpublished() {

	    $path = File::getUserSandboxPath();
	    $unpublished = array();
		
		// Loop through all the .html files in the published folder  
		foreach (scandir($path) as $file) { 
			// We only want to show the files that the user can edit (html files)
			if(File::fileExtension($file) == "html") {
				$unpublished[] = array("name" => urlencode(File::fileName($file)), "path"=> $path . $file);
			}
		}

		return $unpublished;
	
	}


}

?>
