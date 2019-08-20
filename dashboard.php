<?php
include("functions.php");
$AV = new AV;
if(!$AV->userLoggedIn())
	$AV->redirect("/");

if(isset($_POST['title'])
	and isset($_POST['description'])
	and isset($_POST['from'])
	and isset($_POST['to'])
	and isset($_POST['when'])) {

	$title = $AV->escapeString($_POST['title']);
	$description = $AV->escapeString($_POST['description']);
	$from = $AV->escapeString($_POST['from']);
	$to = Array(
		'city' => $AV->escapeString($_POST['city']),
		'country' => $AV->escapeString($_POST['country'])
	);
	$when = $AV->escapeString($_POST['when']);

	$AV->currentUserInsertTrip($title, $description, $from, $to, $when);
	$AV->redirect();
}
$AV->parseHTMLContent();
$AV->templateHeader("{lang['welcome-back']} {user['name']} {lang['on']} {{website_name}}", "", Array("profile"));
?>
<div class="profile">
	<div class="row">
		<div class="col-md-12">
			<form id="insertTrip" action="<?php print $_SERVER['REQUEST_URI'] ?>" method="POST">
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
					<input type="hidden" name="city">
					<input type="hidden" name="country">
					<input type="text" name="to" class="form-control input" id="gMapsAutocompleteTo" placeholder="{lang['to']}" /><br />
				</div>
				<div class="col-md-6">
					<input type="text" name="when" class="form-control input" id="datePicker" placeholder="{lang['when']}" /><br />
				</div>
				<div class="col-md-6">
					<button class="btn btn-primary btn-block">{lang['insert-trip']}</button><br />
				</div>
			</div>
			</form>
		</div>
	</div>
	<div class="row feed">
		<div class="col-md-12">
			<div class="row trips">
				<?php
				$trips = $AV->getTrips();
				if($trips != false): ?>
				<div class="col-md-12">
					<h2>{lang['trips']}</h2>
				</div>
				<?php
				foreach($trips as $trip) {
					$destination = Array($trip['city'], $trip['country']);
					$destination = implode(", ", $destination);
					print "
				<div class=\"trip col-md-4\">
					<a href=\"{{url}}/viaggi/{$trip['slug']}/\">
					<div class=\"background\">
						<div class=\"overlay\"></div>
						<img src=\"" . $AV->getTripImage($trip['city'], $trip['country']) . ")\" />
					</div>
					<div class=\"info\">
						<div class=\"title\">
							{$trip['title']}
						</div>
						<br />
						<div class=\"destination\">
							{$destination}
						</div>
					</div>
					</a>
				</div>";
				}
				endif; ?>
			</div>
		</div>
	</div>
</div>
<?php $AV->templateFooter(); ?>