<?php
include("functions.php");
$AV = new AV;
if(!$AV->userLoggedIn())
	$AV->redirect("/");

if($AV->currentUser['id'] != 1)
	die("Piattaforma in manutenzione");

$AV->parseHTMLContent();
$AV->templateHeader("Bentornato {user['name']} su {{website_name}}");
?>

<?php $AV->templateFooter(); ?>