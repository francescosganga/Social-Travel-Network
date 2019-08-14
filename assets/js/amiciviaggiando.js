$(document).ready(function() {
	var options = {
		types: ['(regions)']
	};

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