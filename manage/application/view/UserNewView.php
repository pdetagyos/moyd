<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Add a New User</h2>

<p>Please enter all the information for your new user. Make sure that the username doesn't contain any spaces or any punctuation marks 
or symbols (letters and numbers only, please). When you are done entering the user information, click on the <strong>Continue</strong> button.</p>
<br/>
<div class="centered">
	
	<form id="frmUserNew" method="post" action="/manage/userNew">

		<div id="errors">
			Sorry - you cannot create a new user yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table width="100%" class="tableForm">
		<tr>
			<td><label for="username">Username:</label></td>
			<td align="left"><?php Form::textbox('username', $pageState, 15, 15); ?></td>
		</tr>
		<tr>
			<td><label for="password">Password:</label></td>
			<td align="left"><?php Form::textbox('password', $pageState, 15, 15); ?></td>
		</tr>
		<tr>
			<td><label for="fullname">Full Name:</label></td>
			<td align="left"><?php Form::textbox('fullname', $pageState, 25, 50); ?></td>
		</tr>
		<tr>
			<td><label for="email">Email:</label></td>
			<td align="left"><?php Form::textbox('email', $pageState, 25, 50); ?></td>
		</tr>
		<tr>
			<td><label for="homefolder">Home Folder:</label></td>
			<td align="left"><?php Form::textbox('homefolder', $pageState, 30, 50); ?><br/>
				<span class="small">Be sure to start with a leading slash, and leave off a trailing slash. (e.g. /maindir/subdir) For site admins, enter a single slash: /</span>
			</td>
		</tr>
		<tr>
			<td><label for="foldermgr">Folder Manager:</label></td>
			<td align="left"><?php Form::checkbox('foldermgr', $pageState, 'foldermgr'); ?></td>
		</tr>
		<tr>
			<td><label for="adveditor">Advanced Editor:</label></td>
			<td align="left"><?php Form::checkbox('adveditor', $pageState, 'adveditor'); ?></td>
		</tr>
		<tr>
			<td class="centered" colspan="2"><a href="/manage/userMgmt">Cancel</a>&nbsp;&nbsp;&nbsp;<input name="btnCreate" type="submit" value="Continue" /></td>
		</tr>
		</table>

	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>

