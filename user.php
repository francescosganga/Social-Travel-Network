<?php
include("functions.php");
$AV = new AV;

$userData = $AV->userData($AV->escapeString($_REQUEST['param']));
if(!isset($_REQUEST['param']) and $userData == false)
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['profile-of']} {$userData['username']}", "", Array("profile"));
?>
<div class="profile">
	<div class="row">
		<div class="col-md-4">
			<img class="avatar" src="{{url}}<?php print $userData['avatar']; ?>" />
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<h1><?php print $userData['username']; ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<strong>0</strong> {lang['trips']}
				</div>
				<div class="col-md-4">
					<strong>0</strong> {lang['follower']}
				</div>
				<div class="col-md-4">
					<strong>0</strong> {lang['following']}
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-12">
					<h3><?php print $userData['name'] . " " . $userData['surname']; ?></h3>
				</div>
			</div>
		</div>
</div>
<?php
$AV->templateFooter();
?>