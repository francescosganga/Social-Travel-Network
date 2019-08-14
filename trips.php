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
$AV->templateHeader("{lang['trip']} \"{$tripData['title']}\"", $tripData['description'], Array("trips"));
?>
<div class="trip">
	<div class="header" style="background-image: url({{url}}/assets/images/trips/<?php print strtolower($tripData['country']) ?>.jpg">
		<div class="overlay"></div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="row">
					<div class="col-md-12">
						<h1><?php print $tripData['title']; ?></h1>
						<h2><?php print $tripData['description']; ?></h2>
						<i>
							<?php if($tripData['partecipants']['total'] == 1): ?>
							Parteciperà <?php print $tripData['partecipants'][0]['name'] ?> a questo viaggio.
							<?php else: ?>
							Parteciperanno <?php print $tripData['partecipants'][0]['name'] ?> e altre <?php print $tripData['partecipants']['total'] ?> persone a questo viaggio.
							<?php endif; ?>
						</i>
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
			<div class="col-md-2"></div>
		</div>
	</div>
</div>
<?php
$AV->templateFooter();
?>