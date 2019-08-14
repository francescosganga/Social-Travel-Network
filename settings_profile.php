<?php
include("functions.php");
$AV = new AV;

if(!$AV->userLoggedIn())
	$AV->redirect("/");

if(isset($_POST['city']) and isset($_POST['privacy'])) {
	$fields = Array(
		'city' => $AV->escapeString($_POST['city']),
		'privacy' => $AV->escapeString($_POST['privacy'])
	);

	if($_FILES['avatar']['size'] != 0) {
		$fields['avatar'] = $AV->uploadImage($_FILES['avatar']);
	}

	$AV->userUpdate($fields);

	//$AV->redirect($_SERVER['REQUEST_URI']);
}
$AV->parseHTMLContent();
$AV->templateHeader("{lang['profile-settings']}", "", Array("settings"));

$userData = $AV->currentUser;
?>
<div class="settings">
	<form action="" method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<input type="text" class="form-control input" placeholder="{lang['name']}" value="<?php print $userData['name'] ?>" disabled/><br />
			<input type="text" class="form-control input" placeholder="{lang['surname']}" value="<?php print $userData['surname'] ?>" disabled/><br />
			<input type="text" name="city" class="form-control input" placeholder="{lang['city']}" value="<?php print $userData['city'] ?>" />
		</div>
		<hr />
		<div class="form-group">
			<input type="text" class="form-control input" placeholder="{lang['username']}" value="<?php print $userData['username'] ?>" disabled/><br />
			<input type="text" class="form-control input" placeholder="{lang['email']}" value="<?php print $userData['email'] ?>" disabled/><br />
			<input type="text" class="form-control input" placeholder="{lang['phone']}" value="<?php print $userData['phone'] ?>" disabled/>
		</div>
		<hr />
		<div class="row">
			<div class="col-md-4">
				<img src="{{url}}/<?php print $userData['avatar'] ?>" />
			</div>
			<div class="col-md-8">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="inputGroupFileAddon01">Avatar</span>
					</div>
					<div class="custom-file">
						<input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="avatar" />
						<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
					</div>
				</div>
			</div>
		</div>
		<hr />
		<div class="form-group">
			<h3>{lang['profile-privacy']}</h3>
			<p style="font-style: italic">{lang['profile-privacy-descr']}</p>
			<select type="text" name="privacy" class="form-control select" data-selected="<?php print $userData['privacy'] ?>">
				<option value="1">{lang['friends-only']}</option>
				<option value="2">{lang['all']}</option>
			</select>
		</div>
		<div class="form-group">
			<button class="btn btn-primary">{lang['save-changes']}</button>
		</div>
	</form>
</div>
<?php
$AV->templateFooter();
?>