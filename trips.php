<?php
include("functions.php");
$AV = new AV;

if(!isset($_REQUEST['param']))
	$AV->redirect("/");

$trip = $AV->escapeString($_REQUEST['param']);
$trip = explode("-id", $trip);
$trip = $trip[1];

$tripData = $AV->tripData($trip);
$tripData['destination'] = Array($tripData['city'], $tripData['country']);
$tripData['destination'] = implode(", ", $destination);
if($tripData == false)
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['profile-of']} {$tripData['title']}", $tripData['description'], Array("trips"));
?>
<div class="trip">
	<div class="row trip-background" style="background: url({{url}}/assets/images/trips/<?php print strtolower($tripData['country']) ?>.jpg">
		<div class="col-md-4"></div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<h1><?php print $tripData['title']; ?></h1>
					<h2><?php print $tripData['description']; ?></h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<h3><?php print $tripData['destination'] ?></h3>
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
</div>
<?php
$AV->templateFooter();
?>