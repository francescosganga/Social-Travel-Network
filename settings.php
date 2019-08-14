<?php
include("functions.php");
$AV = new AV;

if(!isset($_REQUEST['param']) or !$AV->userLoggedIn())
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['profile-settings']}", "", Array("settings"));
?>
<div class="settings">
	banane
</div>
<?php
$AV->templateFooter();
?>