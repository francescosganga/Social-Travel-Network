<?php
include("functions.php");
$AV = new AV;

$userData = $AV->userData($AV->escapeString($_REQUEST['param']));
if(isset($_REQUEST['verify']) and strlen($_REQUEST['verify']) == 32) {
	$verifyHash = $AV->escapeString($_REQUEST['verify']);
	$AV->verifyUser($verifyHash);
	$AV->redirect("/");
}

if(isset($_REQUEST['param']) and $userData == false)
	$AV->redirect("/");

$AV->parseHTMLContent();
$AV->templateHeader("{lang['profile-of']} {$userData['username']}", "", Array("profile"));
?>
<div class="profile">
	<div class="row">
		<div class="col-md-4 avatar">
			<img src="{{url}}/<?php print $userData['avatar']; ?>" />
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
	<div class="row feed">
		<div class="col-md-12">
			<div class="row trips">
				<div class="col-md-12">
					<h2>{lang['trips']}</h2>
				</div>
			<?php
				$trips = $AV->getTrips($userData['id']);
				foreach($trips as $trip) {
					$destination = Array($trip['city'], $trip['country']);
					$destination = implode(", ", $destination);
					print "
				<div class=\"trip col-md-4\">
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
				</div>";
				}
			?>
			</div>
		</div>
</div>
<?php
$AV->templateFooter();
?>