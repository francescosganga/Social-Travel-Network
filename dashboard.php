<?php
include("functions.php");
$AV = new AV;
if(!$AV->userLoggedIn())
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['welcome-back']} {user['name']} {lang['on']} {{website_name}}", "", Array("feed"));
?>
<div class="feed">
	<div class="row">
		<div class="col-md-12">
			<form action="<?php print $_SERVER['REQUEST_URI'] ?>" method="POST">
			<div class="row">
				<div class="col-md-12">
					<h3>{lang['insert-a-trip']}</h3>
					<input type="text" name="title" class="form-control input" placeholder="{lang['title']}" /><br />
					<textarea name="description" class="form-control textarea" placeholder="{lang['description']}"></textarea><br />
				</div>
				<div class="col-md-6">
					<input type="text" name="from" class="form-control input" id="gMapsAutocompleteFrom" placeholder="{lang['from']}" /><br />
				</div>
				<div class="col-md-6">
					<input type="text" name="from" class="form-control input" id="gMapsAutocompleteTo" placeholder="{lang['to']}" /><br />
				</div>
				<div class="col-md-6">
					<input type="text" name="when" class="form-control input" id="datePicker" placeholder="{lang['when']}" /><br />
				</div>
				<div class="col-md-6">
					<button class="btn btn-primary btn-block">{lang['insert-trip']}</button><br />
				</div>
			</form>
		</div>
	</div>
</div>
<?php $AV->templateFooter(); ?>