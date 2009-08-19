<?php
	$this->addScript('/manage/application/static/js/jquery.js');
?>

<div id="backToSiteMgmt"><a href="/manage/welcome">Back to Site Management</a></div>

<h1>Welcome, <?php echo $this->session['usernameFriendly'] ?></h1><br/>

<h2>The current content management users are listed below.</h2>

Click on the name of a user to edit their information or settings.


<div id="messages">
	<?php 	if ($this->messages) { foreach ($this->messages as $msg) { ?>
		<div class="message"><?php print $msg . "<br/>"; ?></div>
	<?php	}	} ?>
</div>

<table width="100%">
<tr>
	<td align="center">
		<div class="sectionTitle">Site Content Managers</div>
		<table width="100%">
			<tr class="sectionHeader">
				<th>&nbsp;Username</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>Home Folder</th>
				<th>Create Folder?</th>
				<th>Advanced Editor?</th>
				<th>&nbsp;</th>
			</tr>
<?php
	// loop through all the user data, building a row for each user
	$row = "row0";
	foreach ($this->userData as $userInfo) { 
		// Build a row for the file
		$fileRow = '<tr class="' . $row .'">';
		$fileRow .= '<td align="left" width="10%">&nbsp;' . $userInfo['username'] . '</td>';
		$fileRow .= '<td align="left" width="24%"><a href="/manage/userEdit/get/user/' . $userInfo['username'] . '">' . $userInfo['friendlyName'] . '</a></td>';
		$fileRow .= '<td align="left" width="20%">' . $userInfo['email'] . '</td>';
		$fileRow .= '<td align="left" width="25%">' . $userInfo['userPath'] . '</td>';
		$fileRow .= '<td align="center" width="8%">' . (($userInfo['directoryMode'] == 'BASIC') ? 'No' : 'Yes') . '</td>';
		$fileRow .= '<td align="center" width="8%">' . (($userInfo['editorMode'] == 'BASIC') ? 'No' : 'Yes') . '</td>';
		$fileRow .= '<td align="center" width="5%"><span class="dellink"><a href="/manage/userDelete/get/userToDelete/' . $userInfo['username'] . '">Del&nbsp;</a></span></td>'; 				
		$fileRow .= '</tr>';
		echo $fileRow;
		if ($row == 'row0') $row = 'row1'; else $row = 'row0'; 
	}
?>
		<tr>
			<td>&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<div id="buttons">
	<div class="buttonLink"><a href="/manage/userNew"><div class="addNewUserLink"></div></a></div>
	<div class="buttonLink" style="margin-top:12px;"><a id="deleteLnk" href="#">Show Delete Links</a></div>
</div>

<script type="text/javascript">

	$(document).ready(function() {
   		$("#deleteLnk").click(function() {
     		$(".dellink").fadeIn("normal");
   		});
 	});

</script>

<?php 	if ($this->messages) {	?>
		<script type="text/javascript">$("#messages").fadeIn("normal");</script>
<?php	} 	?>
