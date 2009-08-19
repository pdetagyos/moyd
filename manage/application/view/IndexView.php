<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<div class="centered">
	<br />
	<h2>Welcome!</h2>
	<div class="centered">
		Please select your name from the list below and enter your password. Then click on the Log in button.
	</div>
	<form name="loginForm" method="POST" action="/manage/index">

		<div id="messages">
			<?php 	if ($this->messages) { foreach ($this->messages as $msg) { ?>
				<div class="message"><?php echo $msg . '<br/>'; ?></div>
			<?php	}	} ?>
		</div>
		
		<div id="errors">
			<?php 	if ($this->errors) { foreach ($this->errors as $err) {	?>
				<div class="error"><?php echo $err . '<br/>'; ?></div>
			<?php	}	}	?>
		</div>

		<div id="indexDiv">
		<div id="formDiv">
			<div class="row">
				<img src="/manage/application/static/images/one.png" />
				<div class="rowLabel">Name:</div>
				<div class="rowData">
					<select name="username" id="username">
						<option value="none">Select Your Name</option>
						<?php	foreach($this->userList as $uname => $fname) {	?>
							<option value="<?php echo $uname; ?>"><?php echo $fname; ?></option>
						<?php	}	?>
					</select>
				</div>
			</div>
			<div class="row">
				<img src="/manage/application/static/images/two.png" />
				<div class="rowLabel">Password:</div>
				<div class="rowData">
					<input name="password" type="password" />&nbsp;&nbsp;
					<a href="/manage/forgotPassword">Oops! I've forgotten my password!</a>
				</div>
			</div>
			<div class="row">
				<img src="/manage/application/static/images/three.png" />
				<div class="rowLabel">
					<input name="btnLogin" type="submit" value="Log in" />
				</div>
			</div>
		</div>
		</div>
		
	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>

<?php 	if ($this->messages) {	?>
		<script type="text/javascript">$("#messages").fadeIn("normal");</script>
<?php	} 	?>
