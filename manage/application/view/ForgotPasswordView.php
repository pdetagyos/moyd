<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Reset your Password</h2>

<br/><br/>

<p>Please select your name from the list, and enter your email address in the second box.  The email address will be compared to the address assigned to your account, and if it matches, your password will be reset. The new password will be sent to you via email.<br/><br/> 
</p>

<br/><br/>

<div class="centered">
	<form name="newFolderForm" method="POST" action="/manage/forgotPassword">

		<div id="errors">
			Sorry - you cannot create a new page yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table name="newPageTable" width="90%">
			<tr>
				<td><img src="/manage/application/static/images/one.png" /></td>
				<td class="right">Your Name:</td>
				<td class="left">
					<select name="username">
						<option value="none">Select Your Name</option>
						<?php	foreach($this->userList as $uname => $fname) {	?>
							<option value="<?php echo $uname ?>"><?php echo $fname ?></option>
						<?php	}	?>
					</select>
				</td>
			</tr>
			<tr>
				<td><img src="/manage/application/static/images/two.png" /></td>
				<td class="right">Your Email:</td>
				<td class="left"><?php Form::textbox('email', $pageState, 20, 20); ?></td>
			</tr>
			<tr>
				<td class="centered"><img src="/manage/application/static/images/three.png" /></td>
				<td class="centered" colspan="2"><input name="btnReset" type="submit" value="Get My Password" /></td>
			</tr>
		</table>

	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	}	?>
