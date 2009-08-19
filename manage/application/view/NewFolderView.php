<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Add a New Folder</h2>

<br/><br/>

<p>Please enter the name of your new folder. Make sure that it doesn't contain any spaces or any punctuation marks 
or symbols (letters and numbers only, please). After you enter the folder name, click on the 
<strong>Create Folder</strong> button.</p>

<br/><br/>

<div class="centered">
	<form name="newFolderForm" method="POST" action="/manage/newFolder">

		<div id="errors">
			Sorry - you cannot create a new page yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table name="newPageTable" width="90%" class="tableForm">
			<tr>
				<td><img src="/manage/application/static/images/one.png" /></td>
				<td class="right">New Folder Name:</td>
				<td class="left"><?php Form::textbox('newFolderName', $pageState, 20, 20); ?></td>
			</tr>
			<tr>
				<td class="centered"><img src="/manage/application/static/images/two.png" /></td>
				<td class="centered" colspan="2"><a href="/manage/welcome">Cancel</a>&nbsp;&nbsp;&nbsp;<input name="btnCreate" type="submit" value="Create Folder" /></td>
			</tr>
		</table>

	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	}	?>
