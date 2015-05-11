/* globals $, cityPlaceholder:false */
$(function() {
	'use strict';

	// Should be done with a selector control such as...
	$('.instore-toggle').on('change', function () {
		$('.modal-conditional-block').toggleClass('active');

		setTimeout(function () {
			$('.modal-conditional-block.active').find('input[autofocus]').first().focus();
		}, 500);

	});

	$('input[name="MultiCheckoutForm[shippingPostal]"]').on('keyup', function () {
		var country = $('#MultiCheckoutForm_shippingCountryCode :selected').val();

		if (this.value.length > 2) {
			var zippoOptions = {'postal': this.value, 'country': country};
			zippothatnow(zippoOptions);
		}

		if (this.value.length < 3) {
			eraseCity();
			$('#MultiCheckoutForm_shippingStateCode').val('');
		}

	});

	$('#shipping').on('submit', function()	{
		//WS-3056 - Perform zippo lookup for state on submit of shipping address
		// form and billing address form
		var zippoOptions = {'async': false, 'eraseCity': false};
		zippoLookup(zippoOptions);
	});

	$('#MultiCheckoutForm_shippingCity').change(function () {
		if ($('#MultiCheckoutForm_shippingCity :selected').attr('value') === 'none') {
			eraseCity();
		}
	});

	var $shippingOptions = $('input[name=shippingOption]:checked');
	if ($shippingOptions.length === 0) {
		$($('input[name=shippingOption]')[0]).next().click();
		$($('input[name=shippingOption]')[0]).prop('checked', true);
	}

	function eraseCity() {
		var textInput = $('<input placeholder="'+cityPlaceholder+'" required="required" name="MultiCheckoutForm[shippingCity]" id="MultiCheckoutForm_shippingCity" type="text" />');
		$('#ChooseCity').html(textInput);
		$('#MultiCheckoutForm_shippingCity').val('');
	}

	function zippothatnow(options) {

		// we can add to the switch list as necessary
		switch (options.country) {

			// for Great Britain and Canada, Zippopotam only uses the first 3 characters in the
			// query URL. In case someone pastes in the full postal, we account for it here
			case 'GB':
			case 'CA':
				options.postal = options.postal.substring(0, 3);
				break;
		}

		$.ajax({
			url: '//api.zippopotam.us/' + options.country + '/' + options.postal,
			cache: false,
			dataType: 'json',
			async: options.async
		}).success(function (data) {
			if (data.places.length > 1) {
				$('#MultiCheckoutForm_shippingStateCode').val(data.places[0]['state abbreviation']);
			}
			else {
				if (options.eraseCity === true) {
					eraseCity();
				}
				var places = data.places[0];
				$('#MultiCheckoutForm_shippingStateCode').val(places['state abbreviation']);
			}
		});
	}

	function createOptions(places, selector)
	{
		for (var val in places) {
			if (places.hasOwnProperty(val)) {
				$('<option />', {value: places[val]['place name'], text: places[val]['place name']}).appendTo(selector);
			}
		}
	}

	function zippoLookup(options)
	{
		var postal = $('#MultiCheckoutForm_shippingPostal').val();
		var country = $('#MultiCheckoutForm_shippingCountryCode :selected').val();

		if (postal !== null && postal.length > 2) {
			options.country =  country;
			options.postal = postal;
			zippothatnow(options);
		}
	}
});
