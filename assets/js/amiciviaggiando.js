$(document).ready(function() {
	$("#datePicker").each(function(i) {
		$(this).datepicker({
			dateFormat: 'dd-mm-yy'
		});
	});

	var options = {
		types: ['(regions)']
	};

	var dashboardfrom = new google.maps.places.Autocomplete($("#gMapsAutocompleteFrom")[0], options);
	var dashboardto = new google.maps.places.Autocomplete($("#gMapsAutocompleteTo")[0], options);
	var autocomplete = new google.maps.places.Autocomplete($("#gMapsAutocomplete")[0], options);
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

	$("select[data-selected]").each(function(i) {
		$(this).find("option[value='" + $(this).attr("data-selected") + "']").prop("selected", true);
	})
});