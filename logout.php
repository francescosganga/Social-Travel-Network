<?php
include("functions.php");
$AV = new AV;
$AV->logout();
$AV->redirect("/");
?>