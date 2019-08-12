<?php
include("functions.php");
$AV = new AV;
if(!$AV->userLoggedIn())
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['welcome-back']} {user['name']} {lang['on']} {{website_name}}");
?>

<?php $AV->templateFooter(); ?>