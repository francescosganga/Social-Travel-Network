<?php
include("functions.php");
$AV = new AV;
$AV->parseHTMLContent();
if(isset($_POST['name'])
	and isset($_POST['surname'])
	and isset($_POST['city'])
	and isset($_POST['username'])
	and isset($_POST['password'])
	and isset($_POST['email'])
	and isset($_POST['phone'])) {

	$AV = new AV;

	$name = $AV->escapeString($_POST['name']);
	$surname = $AV->escapeString($_POST['surname']);
	$city = $AV->escapeString($_POST['city']);
	$username = $AV->escapeString($_POST['username']);
	$password = md5($AV->escapeString($_POST['password']));
	$email = $AV->escapeString($_POST['email']);
	$phone = $AV->escapeString($_POST['phone']);

	if($AV->userRegistration($name, $surname, $city, $username, $password, $email, $phone)) {
		print "true";
	} else {
		print "false";
	}
} else {
?>
<html>
	<head>
		<title>{{website_name}} - Registrazione</title>

		<meta name="description" content="Iscriviti e inizia subito a trovare o cercare nuovi compagni per i tuoi viaggi! " />
		<meta property="og:site_name" content="{{website_name}}" />
		<meta property="og:site" content="" />
		<meta property="og:title" content="Registrazione a {{website_name}}" />
		<meta property="og:description" content="Iscriviti e inizia subito a trovare o cercare nuovi compagni per i tuoi viaggi!!" />
		<meta property="og:image" content="" />
		<meta property="og:url" content="" />

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/signup.css" />
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-145412630-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'UA-145412630-1');
		</script>
	</head>
	<body>
		<div class="signup-form">
			<div class="signin-form">
				<div class="signin-logo">
					<img src="{{url}}/assets/images/logo.png" />
				</div>
				<h1>Registrazione</h1>
				<div class="alert alert-global"></div>
				<input type="text" name="name" class="form-control input" placeholder="Nome" /><br />
				<input type="text" name="surname" class="form-control input" placeholder="Cognome" /><br />
				<input type="text" name="city" class="form-control input" placeholder="Città" />
				<hr />
				<input type="text" name="username" class="form-control input" placeholder="Username" /><br />
				<input type="password" name="password" class="form-control input" placeholder="Password" /><br />
				<input type="password" name="verifypassword" class="form-control input" placeholder="Ripeti Password" />
				<hr />
				<input type="text" name="email" class="form-control input" placeholder="Indirizzo Email" /><br />
				<input type="text" name="phone" class="form-control input" placeholder="Telefono" />
				<hr />
				<input type="checkbox" name="privacy" class="checkbox" /> Informativa Privacy<br /><br />
				<input type="checkbox" name="termsofuse" class="checkbox" /> Termini e Condizioni d'Uso
				<hr />
				<button class="btn btn-primary btn-block">Registrati</button>
			</div>
		</div>
		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{{url}}/assets/js/amiciviaggiando.js"></script>
		<script type="text/javascript" src="{{url}}/assets/js/signup.js"></script>
	</body>
</html>
<?php
}
?>