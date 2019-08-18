<html>
	<head>
		<title><?php print $title ?></title>

		<meta name="description" content="<?php print $description ?> " />
		<meta property="og:site_name" content="{{website_name}}" />
		<meta property="og:site" content="" />
		<meta property="og:title" content="<?php print $title ?>" />
		<meta property="og:description" content="<?php print $description ?>" />
		<meta property="og:image" content="" />
		<meta property="og:url" content="" />

		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="{{url}}/assets/css/dashboard.css" />
		<?php print $custom_css; ?>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
		<script src="https://maps.googleapis.com/maps/api/js?key={{google_apikey}}&libraries=places" async defer></script>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id={{google_gaid}}"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '{{google_gaid}}');
		</script>
	</head>
	<body>
		<div class="dashboard">
			<header>
				<div class="row">
					<div class="col-md-4">
						<a href="{{url}}/"><img src="{{url}}/assets/images/logo.png" /></a>
					</div>
					<div class="col-md-4">
						<form id="searchBox" action="{{url}}/cerca/" method="GET">
						<input type="hidden" name="country" />
						<input type="hidden" name="city" />
						<input type="text" id="gMapsAutocomplete" name="s" class="form-control input" placeholder="{lang['where-you-want-to-go']}" />
						</form>
					</div>
					<div class="col-md-4">
						<a href="{{url}}/impostazioni/profilo/"><i class="fa fa-cog"></i></a>&emsp;
						<a href="{{url}}/profilo/{user['username']}/"><i class="fa fa-user"></i></a>
					</div>
				</div>
			</header>