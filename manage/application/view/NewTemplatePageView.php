<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<h2>Provide information about your new page</h2>

<br/><br/>

<p>Please enter the information necessary to complete the template page. After you enter the information, click on the <strong>Create Page</strong> button.</p>
<br/><br/>
<div class="centered">
	<form name="newPageForm" method="POST" action="/manage/newTemplatePage">

		<div id="errors">
			Sorry - you cannot create a new page yet. There are some problems:<br/>
			<?php 	if ($this->errors) { foreach ($this->errors as $err) { ?>
				<div class="error">- <?php print $err; ?></div>
			<?php	}	} ?>
		</div>

		<table width="90%">
			<tr>
				<td valign="top">
					<img src="/manage/application/static/images/one.png" />
				</td>
				<td>
					<table id="templateTable" width="100%">
					<?php	foreach($this->templateFields as $field) {	?>
						<tr style="text-align:right; width=40%;">
							<td><?php echo $field['Question'] ?></td>
							<td style="text-align:left; padding-left:3px;">
							<?php 	switch ($field['Type']) {	
									case "string":
										echo Form::textbox($field['Name'], $this->pageState[$field['Name']], 20, $field['Data']);
										break;
									case "text":
										echo Form::textarea($field['Name'], $this->pageState[$field['Name']], 5, 20);
										break;
									case "list":
										echo Form::listbox($field['Name'], explode(";", $field['Data']), $this->pageState[$field['Name']], false);
										break;
									case "hidden":
										echo Form::hidden($field['Name'], $this->pageState[$field['Name']]);
										break;
									}	?>
							</td>
						</tr>
					<?php	}	?> 
					</table>

				</td>
			</tr>
			<tr>
				<td class="centered"><img src="/manage/application/static/images/two.png" /></td>
				<td class="centered" colspan="2"><input name="btnCreate" type="submit" value="Create Page" /></td>
			</tr>
		</table>

		<input type="hidden" name="pageName" value="<?php echo $this->pageState['pageName'] ?>" />
		<input type="hidden" name="templateName" value="<?php echo $this->pageState['templateName'] ?>" />

	</form>
	<br/><br/>
</div>

<?php 	if ($this->errors) {	?>
		<script type="text/javascript">$("#errors").fadeIn("normal");</script>
<?php	} 	?>

