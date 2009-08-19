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

require_once(Constants::$ROOT_DIR . "fckeditor/fckeditor.php");

/**
	EditPageController - controller that handles page editing 
*/

class EditPageController extends Controller {
	
	// -- Actions --
	
	public function get() {

		Session::verifySession();

		// if editing existing page, load from file 
		$fileData = '';
		$currentPageName = $this->pageState['page'];
		
		// Determine if this page is already published
		$isPublished = false;
		if ($this->pageState['publish'] == '1') {
			$isPublished = true;
		}
		$this->view->isPublished = $isPublished;
		
		// Get the current page's contents
		if ($currentPageName != '') {
			if ($isPublished) {
				$fileData = file_get_contents(File::getUserSandboxPath() . $currentPageName . '.published');
			}
			else {
				$fileData = file_get_contents(File::getUserSandboxPath() . $currentPageName . '.html');
			}
		}
		$this->view->fileData = $fileData;
		
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
		
		// Add to page state
		$this->view->pageState['pageTitle'] = $pageTitle;
		$this->view->pageState['pageDescription'] = $pageDescription;
		$this->view->pageState['pageKeywords'] = $pageKeywords;

		// Assemble a list of pages in the current folder (we hand this to editor control
		$pageList = array();
	    $path = File::getUserSandboxPath();
		foreach (scandir($path) as $file) { 
			// We only want to include the user files
			if ((File::fileExtension($file) == "html") || (File::fileExtension($file) == "published")) {
				$pageList []= File::fileName($file);
			}
		}
		$this->view->pageList = $pageList;

		// Render the view
		$this->view->setMainTemplate('PageTemplate');
		$this->renderView('EditPageView');		

	}

	public function post() {

		Session::verifySession();
		$this->save();

	}
	
	public function save() {

		// Grab the HTML block from the page editor
		$fileName = $this->pageState['fileName'];
		$htmlBlock = stripslashes($this->pageState['pageEditor']);
		
		// Determine if the page is already published
		$isPublished = false;
		$fileExt = '.html';
		if ($this->pageState['publish'] == '1') {
			$isPublished = true;
			$fileExt = '.published';
		}
		
		// Save to the Sandbox file
		file_put_contents(File::getUserSandboxPath() . $fileName . $fileExt, $htmlBlock);
		
		// Load up existing page metadata information (if it exists)
		$handle = @fopen(File::getUserSandboxPath() . $currentPageName . '.config', "r");
		if ($handle) {
			// Read the title
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageTitle = $a[1];	}
			// Read the description
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageDescription = $a[1];		}
			// Read the keywords
			if (!feof($handle)) {	$a = explode("|", fgets($handle)); $pageKeywords = $a[1];	}
			fclose($handle);
		}
		
		// Grab the page metadata that the user entered
		// Page title
		$pageTitle = stripslashes($this->pageState['pageTitle']);
		
		if ($_SESSION['editorMode'] != 'BASIC') {
			// Grab the metatag information
			$pageDescription = stripslashes($this->pageState['pageDescription']);
			$pageKeywords = stripslashes($this->pageState['pageKeywords']);
		}
		
		// Assemble the metadata into a string
		$pageMetadata = "PAGE_TITLE|" . $pageTitle . "\n";
		$pageMetadata .= "PAGE_DESCRIPTION|" . $pageDescription . "\n";
		$pageMetadata .= "PAGE_KEYWORDS|" . $pageKeywords . "\n";
		
		// Store the page metadata in the config file (creating one if it doesn't exist)
		file_put_contents(File::getUserSandboxPath() . $fileName . '.config', $pageMetadata);
		
		
		// If the page has already been published, then run it through the publishing steps
		if ($isPublished) {
			// Load up the page template data
			$templatePageData = file_get_contents(Constants::$ROOT_DIR . 'templates/site/pageTemplate.html');
		
			// Place the data in the template and write the new file to the live site
			$publishedPageContent = str_replace('[[PAGE_CONTENT]]', $htmlBlock, $templatePageData);
			$publishedPageContent = str_replace('[[PAGE_TITLE]]', $pageTitle, $publishedPageContent);
			$publishedPageContent = str_replace('[[PAGE_DESCRIPTION]]', $pageDescription, $publishedPageContent);
			$publishedPageContent = str_replace('[[PAGE_KEYWORDS]]', $pageKeywords, $publishedPageContent);
			
			file_put_contents(File::getUserPublishingPath() . $fileName . '.html', $publishedPageContent);
		}
		
		// Then return to the Welcome page
		header("Location:/manage/welcome");

	}
	
	
	// -- Private Methods --
	
	
}