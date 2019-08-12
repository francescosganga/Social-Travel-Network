<?php
include("functions.php");
if(isset($_POST['username']) and isset($_POST['password'])) {
	$AV = new AV;
	$username = $AV->escapeString($_POST['username']);
	$password = $AV->escapeString(md5($_POST['password']));
	if($AV->login($username, $password)) {
		$AV->setSession($username, $password);
		$AV->redirect("/dashboard/");
	}
	else
		$AV->redirect("/index/");
}