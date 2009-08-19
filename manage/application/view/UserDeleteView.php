<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Delete User</h2>

<br/><br/>
<p>Please confirm that you wish to delete the user listed below by entering the word "yes" in the box below and clicking on the Delete button. This will delete the user's account, but will not remove the user's home directory or files.</p>
<br/><br/>
<div class="centered">
	
	<form id="frmUserEdit" method="post" action="/manage/userDelete">

		<div id="errors">
			Sorry - you cannot delete this user yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table width="100%" class="tableForm">
		<tr>
			<td><label for="username">Delete this User?</label></td>
			<td><?php print $this->pageState['userToDelete']; ?></td>
		</tr>
		<tr>
			<td><label for="confirm">Enter "yes" to confirm deletion.</label></td>
			<td><?php Form::textbox('confirm', $this->pageState, 20, 20); ?></td>
		</tr>
		<tr>
			<td class="centered" colspan="2"><a href="/manage/userMgmt">Cancel</a>&nbsp;&nbsp;&nbsp;<input name="btnDelete" type="submit" value="Delete" /></td>
		</tr>
		</table>
		<input type="hidden" name="userToDelete" value="<?php print $this->pageState['userToDelete']; ?>">
	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>
