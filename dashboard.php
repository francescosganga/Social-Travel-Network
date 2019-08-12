<?php
include("functions.php");
$AV = new AV;
if(!$AV->userLoggedIn())
	$AV->redirect("/");

if($AV->currentUser['id'] != 1)
	die("{lang['maintenance-mode']}");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['welcome-back']} {user['name']} {lang['on']} {{website_name}}");
?>

<?php $AV->templateFooter(); ?>