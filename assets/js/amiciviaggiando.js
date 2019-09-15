$("#gMapsAutocomplete, #gMapsAutocompleteMobile").focus(function() {
	var options = {
		types: ['(regions)']
	};

	var autocomplete = new google.maps.places.Autocomplete(document.getElementById("gMapsAutocomplete"), options);
	var autocompleteMobile = new google.maps.places.Autocomplete(document.getElementById("gMapsAutocompleteMobile"), options);

	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		var place = autocomplete.getPlace();

		$("#searchBox input[name='city']").val("");
		$("#searchBox input[name='country']").val("");

		$.each(place.address_components, function(key, value) {
			if(value.types.includes("locality"))
				$("#searchBox input[name='city']").val(value.long_name);

			if(value.types.includes("country"))
				$("#searchBox input[name='country']").val(value.long_name);
		});

		$("#searchBox").submit();
	});

	google.maps.event.addListener(autocompleteMobile, 'place_changed', function() {
		var place = autocompleteMobile.getPlace();

		$("#searchBox input[name='city']").val("");
		$("#searchBox input[name='country']").val("");

		$.each(place.address_components, function(key, value) {
			if(value.types.includes("locality"))
				$("#searchBox input[name='city']").val(value.long_name);

			if(value.types.includes("country"))
				$("#searchBox input[name='country']").val(value.long_name);
		});

		$("#searchBox").submit();
	});
});

$("#gMapsAutocompleteTo").focus(function() {
	var options = {
		types: ['(regions)']
	};

	var dashboardto = new google.maps.places.Autocomplete(document.getElementById("gMapsAutocompleteTo"), options);

	google.maps.event.addListener(dashboardto, 'place_changed', function() {
		var place = dashboardto.getPlace();

		$("#insertTrip input[name='city']").val("");
		$("#insertTrip input[name='country']").val("");

		$.each(place.address_components, function(key, value) {
			if(value.types.includes("locality"))
				$("#insertTrip input[name='city']").val(value.long_name);

			if(value.types.includes("country"))
				$("#insertTrip input[name='country']").val(value.long_name);
		});
	});
});

$("#gMapsAutocompleteFrom").focus(function() {
	var options = {
		types: ['(regions)']
	};

	var dashboardfrom = new google.maps.places.Autocomplete(document.getElementById("gMapsAutocompleteFrom"), options);
});

$(document).ready(function() {
	$("#datePicker").each(function(i) {
		$(this).datepicker({
			dateFormat: 'dd-mm-yy'
		});
	});

	$("select[data-selected]").each(function(i) {
		$(this).find("option[value='" + $(this).attr("data-selected") + "']").prop("selected", true);
	})
});