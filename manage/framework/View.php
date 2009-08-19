<?php

class View extends ArrayObject {

	// Members --------------------------------------------------------------------
	
	protected $_styleSheets = array();
	protected $_scriptFiles = array();
	protected $_mainTemplate;
	protected $_subTemplates = array();
	protected $_renderedFragments = array();
	
	// Public Interface -----------------------------------------------------------

	public function __construct() {
		parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
	}
	
	public function addStyleSheet($file) {
		$this->_styleSheets[] = $file;
	}

	public function addScript($file) {
		$this->_scriptFiles[] = $file;
	}
	
	public function setMainTemplate($templateName) {
		$this->_mainTemplate = $templateName;		
	}
	
	public function setSubTemplate($subTemplateName, $tagToReplace) {
		$this->_subTemplates[$tagToReplace] = $subTemplateName;
	}
	
	public function render($viewName) {

		// Render main template
		$main = '';
		if (strlen($this->_mainTemplate) > 0) {
			ob_start();
			include(Constants::$VIEW_DIR . $this->_mainTemplate . '.php');
			$main = ob_get_clean();
		}

		// Render content - do this here because we made add stuff to headers (CSS and JS files)
		ob_start();
		include(Constants::$VIEW_DIR . $viewName . '.php');
		$content = ob_get_clean();

		// Render header content
		$head = '';
		foreach ($this->_styleSheets as $styleSheetFile) {
			$head .= "<link href='$styleSheetFile' rel='stylesheet' type='text/css' />";
		}
		foreach ($this->_scriptFiles as $scriptFile) {
			$head .= "<script type='text/javascript' src='$scriptFile'></script>";
		}
		$this->_renderedFragments["head"] = $head;

		// Render templates
		foreach ($this->_subTemplates as $tag => $templateName) {
			ob_start();
			include (Constants::$VIEW_DIR . $templateName . '.php');
			$this->_renderedFragments[$tag] = ob_get_clean();
		}
		
		// Put it all together
		$output = $main;
		foreach ($this->_renderedFragments as $tag => $fragmentOutput) {
			$output = str_replace("{{" . $tag . "}}", $fragmentOutput, $output);
		}
		$output = str_replace("{{content}}", $content, $output);

		return $output;
	}
	
}