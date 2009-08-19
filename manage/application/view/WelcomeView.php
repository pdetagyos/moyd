<?php
	$this->addStyleSheet('/manage/application/static/css/welcome.css');
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<div id="logout"><a href="/manage/logout">Log Out</a></div>
<div id="changePassword"><a href="/manage/changePassword">Change Your Password</a></div>

<h1>Welcome, <?php echo $this->session['usernameFriendly'] ?></h1>
<h3>Information about your web pages can be found below.</h3>

<?php	if ($this->session['userPath'] == '') { 	// If admin, then show User Management link ?>
	<div id="manageUsers"><a href="/manage/userMgmt">To manage users, click here</a>.</div>
<?php	}	?>

<div id="youAreHere">
	<strong>You Are Here: </strong>
	<span class="hilite"><?php print Config::siteConfig('SITE_URL') . $this->session['cwd']; ?></span>
</div>

<div id="messages">
	<?php 	if ($this->messages) { foreach ($this->messages as $msg) { ?>
		<div class="message"><?php print $msg . '<br/>'; ?></div>
	<?php	}	} ?>
</div>

<div class="sectionTitle">
	Your Published Pages
	<div class="titleComment">These pages are visible to the public</div>
</div>
<div class="sectionHeader">
	<div class="leftSide">Name</div>
	<div class="rightSide">Actions</div>
</div>

<?php	foreach ($this->folders as $folder) {	
			if ($folder == '..') {
?>
	<div class="folderRow parentFolderRow"><a href="/manage/welcome/navTo/folder/UP">Up to Parent Folder</a></div>
<?php		} else {	?>	
	<div class="folderRow"><a href="/manage/welcome/navTo/folder/<?php print $folder; ?>"><?php print $folder; ?></a></div>
<?php		}
		}	?>

<?php	foreach ($this->published as $unpub) {	?>
	<div class="publishedRow">
		<div class="leftSide"><?php print $unpub['name']; ?></div>
		<div class="rightSide">
			<a class="viewLink" href="<?php print $unpub['path']; ?>" target="_blank">View</a>
			<a class="editLink" href="/manage/editPage/get/page/<?php print $unpub['name']; ?>/publish/1">Edit</a>
			<a class="unpublishLink" href="/manage/welcome/unpublishPage/page/<?php print $unpub['name']; ?>">Unpublish</a>
		</div>
	</div>
<?php	}	?>
	
<?php	if (count($this->published) == 0) {	?>
	<div class="blankRow">You have no published files.</div>
<?php	}	?>

<div class="sectionTitle">
	Your Unpublished Pages
	<div class="titleComment">These pages are NOT visible to the public</div>
</div>
<div class="sectionHeader">
	<div class="leftSide">Name</div>
	<div class="rightSide">Actions</div>
</div>

<?php	foreach ($this->folders as $folder) {	
			if ($folder == '..') {
?>
	<div class="folderRow parentFolderRow"><a href="/manage/welcome/navTo/folder/UP">Up to Parent Folder</a></div>
<?php		} else {	?>	
	<div class="folderRow"><a href="/manage/welcome/navTo/folder/<?php print $folder; ?>"><?php print $folder; ?></a></div>
<?php		}
		}	?>

<?php	foreach ($this->unpublished as $unpub) {	?>
	<div class="unpublishedRow">
		<div class="leftSide"><?php print $unpub['name']; ?></div>
		<div class="rightSide">
			<a class="previewLink" href="/manage/welcome/previewPage/page/<?php print $unpub['name']; ?>" target="_blank">Preview</a>
			<a class="editLink" href="/manage/editPage/get/page/<?php print $unpub['name']; ?>">Edit</a>
			<a class="publishLink" href="/manage/welcome/publishPage/page/<?php print $unpub['name']; ?>">Publish</a>
			<a class="deleteLink" href="/manage/welcome/deletePage/page/<?php print $unpub['name']; ?>">Delete</a>
		</div>
	</div>
<?php	}	?>
	
<?php	if (count($this->unpublished) == 0) {	?>
	<div class="blankRow">You have no unpublished files.</div>
<?php	}	?>

<div id="buttons">
	<div class="buttonLink"><a href="/manage/newPage"><div class="addNewPageLink"></div></a></div>

<?php	if ($this->session['directoryMode'] == 'ADVANCED') {	// Show second button if the user has folder rights ?>
	<div class="buttonLink"><a href="/manage/newFolder"><div class="addNewFolderLink"></div></a></div>
<?php	}	?>
</div>
	
<?php 	if ($this->messages) {	?>
		<script type="text/javascript">$("#messages").fadeIn("normal");</script>
<?php	} 	?>
		