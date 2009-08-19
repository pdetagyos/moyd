<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>


<h2>Change your Password</h2>

<br/><br/>

<p>Please enter your current password in the first box. Then enter your new password in the second box, and re-type it into the third box. This will help to protect against accidentally mistyping your new password.<br/><br/> 
After you enter your new password and confirm it by re-typing it, click on the <strong>Change Password</strong> button.</p>
<br/><br/>

<div class="centered">
	<form name="changePassword" method="POST" action="/manage/changePassword">

		<div id="errors">
			Sorry - we can't change your password yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<div id="formDiv">
			<div class="row">
				<img src="/manage/application/static/images/one.png" />
				<div class="rowLabel">Current Password:</div>
				<div class="rowData">
					<?php Form::pwbox('currentPassword', $pageState, 20, 20); ?>
				</div>
			</div>
			<div class="row">
				<img src="/manage/application/static/images/two.png" />
				<div class="rowLabel">New Password:</div>
				<div class="rowData">
					<?php Form::pwbox('newPassword', $pageState, 20, 20); ?>
				</div>
			</div>
			<div class="row">
				<img src="/manage/application/static/images/three.png" />
				<div class="rowLabel">Re-type New Password:</div>
				<div class="rowData">
					<?php Form::pwbox('confirmPassword', $pageState, 20, 20); ?>
				</div>
			</div>
			<div class="row">
				<div class="rowData" style="margin-left: 100px;">
					<input name="btnLogin" type="submit" value="Change Password" />
				</div>
			</div>
		</div>

	</form>

</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>


