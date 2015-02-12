/* globals $, cityPlaceholder: false */
$(function() {
	'use strict';

	// on browser refresh or in the event of an error, display the
	// subform input fields for the selected payment provider
	var el = $('input[name="MultiCheckoutForm[paymentProvider]"]:checked').val();
	$('.subform.payform_' + el + ' input').show();
	
	// only display the input fields for the selected radio option
	$('input[name="MultiCheckoutForm[paymentProvider]"]').on('change', function() {
		$('.subform input').fadeOut();
		var classProvider = "payform_" + $('input[name="MultiCheckoutForm[paymentProvider]"]:checked').val();
		$('.subform.' + classProvider +' input').fadeIn();
	});

	$('input[name="MultiCheckoutForm[billingPostal]"]').on('keyup', function () {
		var country = $('#MultiCheckoutForm_billingCountry :selected').attr('code');

		if (this.value.length > 2) {
			var zippoOptions = {'postal': this.value, 'country': country};
			zippothatnow(zippoOptions);
		}

		if (this.value.length < 3) {
			eraseCity();
			$('#MultiCheckoutForm_billingStateCode').val('');
		}

	});

	$('#payment').on('submit', function()	{
		//WS-3056 - Perform zippo lookup for state on submit of shipping address
		// form and billing address form
		var zippoOptions = {'async': false, 'eraseCity': false};
		zippoLookup(zippoOptions);
	});

	$('#MultiCheckoutForm_billingCity').change(function() {
		if ($('#MultiCheckoutForm_billingCity :selected').attr('value') === 'none'){
			eraseCity();
		}
	});

	function eraseCity() {
		var textInput = $('<input size="14" placeholder="'+ cityPlaceholder +'" required="required" name="MultiCheckoutForm[billingCity]" id="MultiCheckoutForm_billingCity" type="text" />');
		$('#ChooseCity').html(textInput);
		$('#MultiCheckoutForm_billingCity').val('');
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
				$('#MultiCheckoutForm_billingStateCode').val(data.places[0]['state abbreviation']);
			}
			else {
				if (options.eraseCity === true) {
					eraseCity();
				}
				var places = data.places[0];
				$('#MultiCheckoutForm_billingStateCode').val(places['state abbreviation']);
			}
		});
	}

	function zippoLookup(options)
	{
		var postal = $('#MultiCheckoutForm_billingPostal').val();
		var country = $('#MultiCheckoutForm_billingCountry :selected').attr('code');

		if (postal.length > 2) {
			options.country =  country;
			options.postal = postal;
			zippothatnow(options);
		}
	}
});



