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

if(isset($_POST['partecipate']) and $_POST['partecipate'] == 1) {
	$AV->currentUserPartecipateToTrip($tripData['id']);
	$AV->redirect($_SERVER['REQUEST_URI']);
}

if($AV->userLoggedIn())
	$checkUserPartecipateToTrip = $AV->checkUserPartecipateToTrip($AV->currentUser['id'], $tripData['id']);
else
	$checkUserPartecipateToTrip = false;

$AV->parseHTMLContent();
$AV->templateHeader("{lang['trip']} {$tripData['title']}", $tripData['description'], Array("trips", "comments"));
?>
<div class="trip">
	<div class="header" style="background-image: url({{url}}/assets/images/trips/<?php print strtolower($tripData['country']) ?>.jpg">
		<div class="overlay"></div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-12">
						<h1><?php print $tripData['title']; ?></h1>
						<br />
						<i>
							<?php if($tripData['partecipants']['total'] == 1): ?>
							Parteciperà <a href="<?php print "{{url}}/profilo/{$tripData['partecipants'][0]['username']}/" ?>"><?php print $tripData['partecipants'][0]['name'] ?></a> a questo viaggio.
							<?php else: ?>
							Parteciperanno <a href="<?php print "{{url}}/profilo/{$tripData['partecipants'][0]['username']}/" ?>"><?php print $tripData['partecipants'][0]['name'] ?></a> e altre <?php print $tripData['partecipants']['total'] - 1 ?> persone a questo viaggio.
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
			<div class="col-md-2 d-flex align-items-center">
				<?php if($checkUserPartecipateToTrip and $AV->userLoggedIn()): ?>
					<button class="btn btn-secondary btn-block" disabled>
						<i class="fa fa-check"></i> {lang['partecipating']}
					</button>
				<?php elseif($AV->userLoggedIn() and !$checkUserPartecipateToTrip): ?>
				<form action="<?php print $_SERVER['REQUEST_URI'] ?>" method="POST">
					<input type="hidden" name="partecipate" value="1" />
					<button class="btn btn-primary btn-block">
						<i class="fa fa-plus"></i> {lang['partecipate']}
					</button>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="row trip">
		<div class="col-md-4">
			<h2>{lang['description']}</h2>
			<p style="text-align: justify">
				<?php print $tripData['description']; ?>
			</p>
		</div>
		<div class="col-md-8">
			<h2>{lang['comments']}</h2>
			<div class="row comments">
			<?php
				if($checkUserPartecipateToTrip) {
					$tripData['comments'] = $AV->getTripComments($tripData['id']);
					if($tripData['comments'] != false) {
						foreach($tripData['comments'] as $comment) {
							print "
				<div class=\"col-md-12\">
					{$comment['comment']}
					<p>
						{$comment['time']} {lang['by']} <a href=\"{{url}}/profilo/{$comment['userData']['username']}/\">{$comment['userData']['username']}</a>
					</p>
				</div>";
						}
					} else {
						?>
						{lang['no-comments']}
						<?php
					}
					?>
			</div>
			<div class="row insert-comment">
				<div class="col-md-12">
					<h3>{lang['insert-comment']}</h3>
				</div>
				<?php if($checkUserPartecipateToTrip): ?>
				<div class="col-md-12">
				<form action="<?php print $_SERVER['REQUEST_URI'] ?>" method="POST">
					<textarea name="comment" class="form-control textarea" placeholder="{lang['comment']}"></textarea><br />
					<button class="btn btn-primary btn-block">{lang['insert']}</button>
				</form>
				</div>
				<?php else: ?>
				<div class="col-md-12">
					{lang['only-trips-partecipants-can-comment']}	
				</div>
				<?php endif; ?>
			</div>
					<?php
				} else {
					?>
				<div class="col-md-12">
					{lang['only-registered-users-can-see-comments']}
				</div>
					<?php
				}
			?>
		</div>
	</div>
</div>
<?php
$AV->templateFooter();
?>