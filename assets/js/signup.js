$(document).ready(function() {
	$("button").click(function() {
		name = $("input[name='name']").val();
		surname = $("input[name='surname']").val();
		city = $("input[name='city']").val();
		username = $("input[name='username']").val();
		password = $("input[name='password']").val();
		verifypassword = $("input[name='verifypassword']").val();
		email = $("input[name='email']").val();
		phone = $("input[name='phone']").val();
		privacy = $("input[name='privacy']").is(':checked') ? 1 : 0;
		termsofuse = $("input[name='termsofuse']").is(':checked') ? 1 : 0;

		verifiedForm = true;
		errors = "";
		$(".alert-global").removeClass("alert-danger");
		$(".alert-global").removeClass("alert-success");
		$(".alert-global").html("");

		if(name === "" || surname === "" || password === "" || verifypassword === "" || email === "" || phone === "") {
			verifiedForm = false;
			errors = errors + "Tutti i cambi sono obbligatori<br /><br />";
		}

		if(privacy === 0 || termsofuse === 0) {
			verifiedForm = false;
			errors = errors + "Devi accettare sia l'Informativa Privacy che i Termini e Condizioni d'Uso.<br /><br />";
		}

		if(password != verifypassword) {
			verifiedForm = false;
			errors = errors + "Le password inserite non corrispondono.<br /><br />";
		}

		if(errors !== "") {
			$(".alert-global").addClass("alert-danger");
			$(".alert-global").html(errors);
		}

		if(verifiedForm === true) {
			$.ajax({
				method: "POST",
				url: window.location,
				data: {
					name: name,
					surname: surname,
					city: city,
					username: username,
					password: password,
					email: email,
					phone: phone
				}
			}).done(function(response) {
				if(response == "true") {
					$(".alert-global").addClass("alert-success");
					$(".alert-global").html("Registrazione effettuata con successo. Hai ricevuto una mail con il link per confermare il tuo account.");
				} else {
					$(".alert-global").addClass("alert-danger");
					$(".alert-global").html("Errore durante la registrazione. Controlla che il tuo indirizzo email o il tuo numero di telefono non sia già registrato. Se il problema persiste contattaci.");
				}
			});
		}
	});
});