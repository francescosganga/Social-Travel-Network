<?php
include("functions.php");
$AV = new AV;

$userData = $AV->userData($AV->escapeString($_REQUEST['param']));
if(isset($_REQUEST['verify']) and strlen($_REQUEST['verify']) == 32) {
	$verifyHash = $AV->escapeString($_REQUEST['verify']);
	$AV->verifyUser($verifyHash);
	$AV->redirect("/");
}

if(!isset($_GET['country']) or !isset($_GET['city']))
	$AV->redirect("/");

$country = $AV->escapeString($_GET['country']);
$city = $AV->escapeString($_GET['city']);

if($city == "")
	$destination = $country;
else
	$destination = "{$city}, {$country}";

if(isset($_REQUEST['param']) and $userData == false)
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['searching-trips-to']} {$destination}", "", Array("search", "feed", "profile"));
?>
<div class="search profile">
	<div class="row">
		<div class="col-md-12">
			<h1>{lang['trips-to']} <?php print $destination ?></h1>
		</div>
	</div>
	<div class="row feed">
		<div class="col-md-12">
			<?php
				$trips = $AV->queryTrips($city, $country);
				if($trips != false):
			?>
			<div class="row trips">
				<?php
				foreach($trips as $trip) {
					$destination = Array($trip['city'], $trip['country']);
					$destination = implode(", ", $destination);
					$trip['slug'] = str_replace(" ", "-", strtolower($trip['title'])) . "-id" . $trip['id'];
					print "
				<div class=\"trip col-md\">
					<a href=\"{{url}}/viaggi/{$trip['slug']}/\">
					<div class=\"background\">
						<div class=\"overlay\"></div>
						<img src=\"{{url}}/assets/images/trips/" . strtolower($trip['country']) . ".jpg)\" />
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
			else:
			?>
				{lang['no-trips-to-show']}
			<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php
$AV->templateFooter();
?>