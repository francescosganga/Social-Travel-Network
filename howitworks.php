<?php
include("functions.php");
$AV = new AV;
$AV->parseHTMLContent();
?>
<html>
	<head>
		<title>Come funziona un Social Travel Network - {{website_name}}</title>

		<meta name="description" content="Scopri come e perché utilizzare un Social Travel Network come {{website_name}}" />
		<meta property="og:site_name" content="{{website_name}}" />
		<meta property="og:site" content="" />
		<meta property="og:title" content="Come funziona un Social Travel Network come {{website_name}}" />
		<meta property="og:description" content="Scopri come e perché utilizzare un Social Travel Network come {{website_name}}" />
		<meta property="og:image" content="" />
		<meta property="og:url" content="" />

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/homepage.css" />
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
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<a class="navbar-brand" href="/"><img src="{{url}}/assets/images/logo.png" /></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active">
						<a class="nav-link" href="/"><i class="fa fa-home"></i></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/registrazione/">{lang['registration']}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/come-funziona/">{lang['how-it-works']}</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="homepage">
			<div class="row">
				<div class="col-md-7">
					<div class="container h-100">
  						<div class="row h-100 justify-content-center align-items-center">
							<div class="row">
								<div class="col-md-10 text-center">
									<h1>
										Benvenuto su AmiciViaggiando.it.
									</h1>
									<h5>
										Registrati per organizzare viaggi e trovare dei compagni<br />
										o per partecipare a viaggi gi&agrave; organizzati.
									</h5>
								</div>
							</div>
						</div>
					</div>
				</div> 
				<div class="col-md-5 signin-homepage">
					<div class="row">
						<div class="col-md-10">
							<?php if(!$AV->userLoggedIn()): ?>
							<div class="signin-form">
								<form action="{{url}}/login/" method="POST">
									<input type="text" name="username" class="form-control input" placeholder="{lang['username']}" /><br />
									<input type="password" name="password" class="form-control input" placeholder="{lang['password']}" /><br />
									<input type="checkbox" name="remember" class="checkbox" /> {lang['remember-me']}<br /><br />
									<button class="btn btn-primary btn-block">{lang['login']}</button>
									<br />
									<p style="text-align: center">oppure</p>
									<a href="{{url}}/registrazione/" class="btn btn-dark btn-block">{lang['registration']}</a>
								</form>
								<hr />
								<div class="signin-links">
									<a href="#">{lang['lost-password']}</a><br />
								</div>
							</div>
							<?php else: ?>
							<div class="signin-form loggedIn">
								{lang['login-as']}<br />
								<br />
								<img class="avatar" src="{{url}}/{user['avatar']}" /><br />
								<br />
								<a href="{{url}}/dashboard/" class="btn btn-primary btn-block">{user['username']}</a><br />
								{lang['or']}
								<br /><br />
								<a href="{{url}}/logout/" class="btn btn-secondary btn-block">{lang['logout']}</a>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>Come trovare compagni di viaggio con un Social Travel Network</h1>
				</div>
				<div class="col-md-12">
					<p>
						Pagina in costruzione
					</p>
				</div>
			</div>
		</div>
		<footer>
			<div class="row">
				<div class="col-md-12">
					Copyright &copy; 2019 - All rights reserved.
				</div>
			</div>
		</footer>
		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{{url}}/assets/js/amiciviaggiando.js"></script>
	</body>
</html>