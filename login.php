<?php
include("functions.php");
if(isset($_POST['username']) and isset($_POST['password'])) {
	$AV = new AV;
	$username = $AV->escapeString($_POST['username']);
	$password = $AV->escapeString(md5($_POST['password']));

	$loginHash = $AV->login($username, $password);
	if($loginHash != false) {
		$AV->setSession("login_hash", $loginHash);
		$AV->redirect("/dashboard/");
	}
	else
		$AV->redirect("/index/");
}