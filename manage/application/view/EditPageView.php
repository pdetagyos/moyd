
<form name="pageEditorForm" action="/manage/editPage" method="post">
	<h2>Now Editing Page: <?php print $this->pageState['page']; ?></h2><br/>
	<strong>Page Title:</strong> <?php Form::textbox('pageTitle', $this->pageState, 50, 200); ?><br/>
	(this is what will appear in the title of the browser window when someone views this page)
	<br/><br/>
	
	<?php		
		// Configure the Editor control
		$oFCKeditor = new FCKeditor('pageEditor');
		$oFCKeditor->BasePath = '/manage/fckeditor/';
		$oFCKeditor->Height = '500';
		$oFCKeditor->Value = $this->fileData;
		if ($_SESSION['editorMode'] == 'BASIC') {
			$oFCKeditor->ToolbarSet = 'Simple';
		}
		else {
			$oFCKeditor->ToolbarSet = 'Advanced';
		}
		// Send information to the control for easy link addition
		$oFCKeditor->Config['siteURL'] = Config::siteConfig("SITE_URL");
		$oFCKeditor->Config['cwd'] = $this->session['cwd'];
		$oFCKeditor->Config['pageList'] = implode("|", $this->pageList);
		
		// Create the control on the page
		$oFCKeditor->Create();

		// Show meta fields if the user is in advanced mode
		if ($this->session['editorMode'] != 'BASIC') {
	?>
	<br/>
	Page Description: <?php Form::textbox('pageDescription', $this->pageState, 40, 400); ?>
	(a brief summary of the page)<br/><br/>
	Page Keywords: <?php Form::textbox('pageKeywords', $this->pageState, 42, 400); ?>
	(keywords for search engines to associate with this page)<br/><br/>
	<?php 
		} 
	?>

	<div class="centered">
		<br/>
		<a href="/manage/welcome">Cancel</a>&nbsp;&nbsp;&nbsp;
		<input type="submit" value="I'm Done With My Editing" />
	</div>	

	<input name="fileName" type="hidden" value="<?php echo $this->pageState['page'] ?>" />
	<?php 	if ($this->isPublished) {	?>
		<input name="publish" type="hidden" value="<?php print $this->pageState['publish']; ?>" />
	<?php	}	?>
</form>

