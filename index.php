<?php
include("functions.php");
$AV = new AV;
$AV->parseHTMLContent();
?>
<html>
	<head>
		<title>Trova la giusta compagnia per il tuo viaggio su {{website_name}}</title>

		<meta name="description" content="Il primo Social Travel tutto italiano per viaggiare in compagnia, trova amici e amiche di viaggio! " />
		<meta property="og:site_name" content="{{website_name}}" />
		<meta property="og:site" content="" />
		<meta property="og:title" content="Trova la giusta compagnia per il tuo viaggio su {{website_name}}" />
		<meta property="og:description" content="Il primo Social Travel tutto italiano per viaggiare in compagnia, trova amici e amiche di viaggio!" />
		<meta property="og:image" content="" />
		<meta property="og:url" content="" />

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/style.css" />
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
		<div class="homepage">
			<div class="row">
				<div class="col-md-12">
					<p style="font-size: 24px; font-weight: bold; text-align: center">
						Benvenuto su AmiciViaggiando.it.<br />
						Registrati per organizzare viaggi e trovare dei compagni<br />
						o per partecipare a viaggi gi&agrave; organizzati.
					</p>
				</div>
				<div class="col-md-6">
					<?php if(!$AV->userLoggedIn()): ?>
					<div class="signin-form">
						<div class="signin-logo">
							<img src="{{url}}/assets/images/logo.png" />
						</div>
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
						<div class="signin-logo">
							<img src="{{url}}/assets/images/logo.png" />
						</div>
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
				<div class="col-md-6 homepage-image">
					<img src="{{url}}/assets/images/viaggiare-insieme.jpg" />
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1>Trova subito dei compagni di viaggio</h1>
					<i>Scopri il <strong>Social Travel</strong>!</i>
					<p style="text-align: justify">
						Il Social Travel è un nuovo modo di viaggiare. Consiste nel trovare una persona online per condividere il proprio viaggio. Se anche tu ami conoscere persone nuove, continua la lettura e scopri come funziona <strong>Amici Viaggiando.</strong>.
					</p>
					<p style="text-align: justify">
						Siamo una piattaforma che ti permette di cercare e trovare compagni di viaggio. Puoi iscriverti gratuitamente e puoi inserire un post per ogni viaggio che intendi organizzare. Gli utenti del Social Network ti contatteranno se interessati al tuo viaggio. Ogni utente è commentato dagli altri utenti, attribuendo anche un punteggio da 1 a 5 stelle.
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h2>Cos'è Amici Viaggiando</h2>
					<p style="text-align: justify">
						AmiciViaggiando.it è un Network che mette in contatto le persone che cercano un compagno di viaggio. Registrati gratuitamente, compila il tuo profilo nel modo più completo possibile, e potrai accedere alla piattaforma che ti permetterà di visualizzare i viaggi inseriti dagli altri utenti e richiederne la partecipazione.
					</p>
					<p style="text-align: justify">
						Potrai anche inserire già da subito i viaggi che hai intenzione di fare nei prossimi periodi in modo da iniziare subito a cercare i tuoi <strong>compagni di viaggio</strong>.
					</p>
				</div>
				<div class="col-md-6">
					<div class="image"><img src="{{url}}/assets/images/social-travel.jpg" /></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="image"><img src="{{url}}/assets/images/amiche-in-viaggio.jpg" /></div>
				</div>
				<div class="col-md-6">
					<h3>Cosa puoi fare su Amici Viaggiando</h3>
					<p style="text-align: justify">
						Una volta iscritti puoi aggiungere un post in cui, inserendo le proprie preferenze, sarai in grado di trovare nuovi compagni di viaggio. Puoi inserire un post standard di viaggio, oppure tramite la funzione "esperto" definire in maniera dettagliata varie opzioni di viaggio, vacanza, appuntamento, viaggio di gruppo o per ospitare. Ogni iscritto ha una propria pagina personale in cui il flusso dei viaggi presenti è dato dai propri post e da tutti i post dei compagni di viaggio o degli amici di cui commentiamo o mettiamo un like.
					</p>
					<p style="text-align: justify">
						Diventa la bacheca di viaggio personale, dove sarà facile verificare se i propri contatti hanno pubblicato un post di viaggio.
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h3>Viaggi di Gruppo</h3>
					<p style="text-align: justify">
						È possibile, tramite la funzione "esperto" dalla pagina di inserimento viaggio, inserire un post per un viaggio di gruppo. Una volta definite le opzioni e il numero massimo di viaggiatori possibili, può essere pubblicato. Ogni iscritto può decidere a quel punto se chiedere di partecipare tramite l'apposito pulsante che verra visualizzato nel flusso dei viaggi.
					</p>
					<p style="text-align: justify">
						Quando decidi con chi viaggiare, puoi scegliere se partire con maschi, femmine, coppie oppure se non hai preferenze particolari, con tutti e tre. In base a questa decisione il tuo post sarà reso visibile dal sistema solamente alle persone che rispecchiano le tue preferenze, e se per esempio imposti solo femmine, i maschi non potranno né commentare il tuo viaggio né vedere i commenti inseriti dagli utenti partecipanti.
					</p>
					<h3>Contatti in privato</h3>
					<p style="text-align: justify">
						Su Amici Viaggiando potrai contattare in privato gli altri utenti, utilizzando un sistema di messaggistica interna. Ricordati però che tutti i commenti e le esperienze di viaggio inserite dai nostri amici sono pubbliche ed è possibile inserire commenti e like.
					</p>
				</div>
				<div class="col-md-6">
					<div class="image"><img src="{{url}}/assets/images/avvenutura-amici.jpg" /></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
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