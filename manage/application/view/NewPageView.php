<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<div class="centered">
	<h2>Add a New Page</h2>
</div>
<br/><br/>
<p>	Please enter the name of your new page. Make sure that it doesn't contain any spaces or any punctuation marks 
	or symbols (letters and numbers only, please). After you enter the page name, choose what kind of page you 
	are creating. 
	Then click on the <strong>Continue</strong> button.</p>
<br/><br/>
<div class="centered">
	<form name="newPageForm" method="POST" action="newPage">

		<div id="errors">
			Sorry - you cannot create a new page yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table name="newPageTable" width="90%">
			<tr>
				<td><img src="/manage/application/static/images/one.png" /></td>
				<td class="right">New Page Name:</td>
				<td class="left"><?php print Form::textbox('newFilename', $this->pageState, 20, 20); ?></td>
			</tr>
			<tr>
				<td class="centered"><img src="/manage/application/static/images/two.png" /></td>
				<td class="right">Select a Page Type:</td>
				<td class="left"><?php print Form::listbox_html('templateName', $this->pageTypes, $this->pageState);?></td>
			</tr>
			<tr>
				<td class="centered"><img src="/manage/application/static/images/three.png" /></td>
				<td class="centered" colspan="2">
					<a href="welcome">Cancel</a>&nbsp;&nbsp;&nbsp;
					<input name="btnCreate" type="submit" value="Continue" />
				</td>
			</tr>
		</table>
	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>

