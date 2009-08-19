<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Edit User</h2>

<p>Please modify the user information as you see fit, then click on the <strong>Update</strong> button.</p>
<br/>
<div class="centered">
	
	<form id="frmUserEdit" method="post" action="/manage/userEdit">

		<div id="errors">
			Sorry - you cannot create a new user yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table width="100%" class="tableForm">
		<tr>
			<td><label for="username">Username:</label></td>
			<td align="left"><?php print $this->pageState['_username']; ?></td>
		</tr>
		<tr>
			<td><label for="fullname">Full Name:</label></td>
			<td align="left"><?php Form::textbox('fullname', $this->pageState, 20, 50); ?></td>
		</tr>
		<tr>
			<td><label for="email">Email:</label></td>
			<td align="left"><?php Form::textbox('email', $this->pageState, 20, 50); ?></td>
		</tr>
		<tr>
			<td><label for="homefolder">Home Folder:</label></td>
			<td align="left"><?php Form::textbox('homefolder', $this->pageState, 20, 50); ?></td>
		</tr>
		<tr>
			<td><label for="foldermgr">Folder Manager:</label></td>
			<td align="left"><?php Form::checkbox('foldermgr', $this->pageState, 'foldermgr'); ?></td>
		</tr>
		<tr>
			<td><label for="adveditor">Advanced Editor:</label></td>
			<td align="left"><?php Form::checkbox('adveditor', $this->pageState, 'adveditor'); ?></td>
		</tr>
		<tr>
			<td class="centered" colspan="2"><a href="/manage/userMgmt">Cancel</a>&nbsp;&nbsp;&nbsp;<input name="btnEdit" type="submit" value="Update" /></td>
		</tr>
		</table>

		<input type="hidden" name="_username" value="<?php print $this->pageState['_username']; ?>" />

	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>

