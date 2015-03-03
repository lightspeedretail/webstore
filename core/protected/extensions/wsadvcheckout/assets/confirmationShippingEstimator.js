'use strict';
/* globals $,  calculatingLabel:false, strCalculateButton:false */

/**
 * TODO: Factor out the many similarities between this file and
 * WsShippingEstimator.js.

/**
 * The WsShippingEstimator class handles interaction with the shipping
 * estimator and tooltip showing the shipping options.
 */
function ConfirmationShippingEstimator(options) {

	// Options from the invoking code.
	options = options || {};
	this.class = options.class || null;
	this.getShippingRatesEndpoint = options.getShippingRatesEndpoint || '';
	this.setShippingOptionEndpoint = options.setShippingOptionsEndpoint || '';
	this.shippingOptions = options.shippingOptions || null;
	this.selectedProviderId = options.selectedProviderId || null;
	this.selectedPriorityLabel = options.selectedPriorityLabel || null;
	this.selectedCountryName = options.shippingCountryName || null;
	this.selectedCountryCode = options.shippingCountryCode || null;
	this.shippingCity = options.shippingCity || null;
	this.shippingState = options.shippingState || null;
	this.messages = options.messages || null;
	this.updateOnLoad = options.updateOnLoad || false;
	this.redirectToShippingOptionsUrl = options.redirectToShippingOptionsUrl || null;
	this.shippingOptions = [];

	if (typeof this.class !== 'string') {
		throw new Error('Must provide a string value for the class option.');
	}

	if (this.redirectToShippingOptionsUrl === null) {
		throw new Error('A redirect URL to the shipping options is required');
	}

	this.$shippingEstimate = $('.shipping-estimate');
	this.$taxEstimate = $('.tax-estimate');
	this.$tax1Estimate = $('.tax1-estimate');
	this.$tax2Estimate = $('.tax2-estimate');
	this.$tax3Estimate = $('.tax3-estimate');
	this.$tax4Estimate = $('.tax4-estimate');
	this.$tax5Estimate = $('.tax5-estimate');
	this.$totalEstimate = $('.wsshippingestimator-total-estimate');
	this.cacheTotalEstimateFontSize = this.$totalEstimate.css('font-size');

	// While waiting to get a response from the shipping estimator some fields
	// can have their labels switched to Calculating...
	this.calculatingFields = [
		this.$totalEstimate,
		this.$taxEstimate,
		this.$shippingEstimate,
		this.$tax1Estimate,
		this.$tax2Estimate,
		this.$tax3Estimate,
		this.$tax4Estimate,
		this.$tax5Estimate
	];

	if (this.messages !== null) {
		this.handleMessages(this.messages);
	}

	if (this.updateOnLoad === true) {
		this.updateShippingEstimates();
	}
}
/**
 * Set which set of UI elements are displayed.
 * @param {string} screeId The ID of the screen in this.screens.
 * @param {string} [hideFunction=hide] The function to call on the jQuery selector.
 */
ConfirmationShippingEstimator.prototype.showScreen = function(screenId, hideFunction) {
	var screenSettings = this.screens[screenId],
		elementToShow,
		elementToHide,
		i,
		len;

	hideFunction = hideFunction || 'hide';

	if (screenSettings === undefined) {
		throw new Error('No screen called ' + screenId + ' defined.');
	}

	for (i = 0, len = screenSettings.hide.length; i < len; i += 1) {
		elementToHide = screenSettings.hide[i];
		if (elementToHide.is(':visible')) {
			elementToHide[hideFunction]();
		}
	}

	for (i = 0, len = screenSettings.show.length; i < len; i += 1) {
		elementToShow = screenSettings.show[i];
		if (elementToShow.is(':hidden')) {
			elementToShow.fadeIn();
		}
	}
};

/**
 * Sets the selected country name.
 * @param {string} countryName The name of the country, e.g. "United States".
 */
ConfirmationShippingEstimator.prototype.setSelectedCountry = function(option) {
	this.selectedCountryName = option.text();
	this.selectedCountryCode = option.val();

	this.$selectedCountryLink.html(option.text());

	// this seems like a no-op but our data can be edited from both the add to
	// cart and edit cart forms.
	this.$shippingCountryPicker.val(option.val());
};

/**
 * Add a shipping option to the shipping option selector tooltip.
 * @param {object} shippingOption A shippingOption object returned by Web Store.
 * @param {boolean} checked Whether the shippingOption should be checked.
 */
ConfirmationShippingEstimator.prototype.addShippingOption = function(shippingOption) {
	var li = $('<li>').append(
		$('<label>')
			.addClass('radio')
			.append(
				$('<input>')
					.attr({
						type: 'radio',
						name: 'shipping_option',
						value: shippingOption.speedId,
						'data-formatted-shipping-price': shippingOption.formattedShippingPrice,
						'data-formatted-cart-tax': shippingOption.formattedCartTax,
						'data-formatted-cart-total': shippingOption.formattedCartTotal,
						'data-provider-id': shippingOption.providerId,
						'data-priority-id': shippingOption.priorityId,
						'data-priority-label': shippingOption.priorityLabel
					})
			)
			.append(shippingOption.shippingLabel)
			.append(
				$('<small>').append(shippingOption.formattedShippingPrice)
			)
			.change(function (e) {
				this.selectedShippingOption(e.target);
			}.bind(this))
	);

	this.$shippingOptions.find('ol').append(li);
};

/**
 * Toggle between showing and not showing the shipping options tooltip.
 */
ConfirmationShippingEstimator.prototype.toggleShowShippingOptions = function() {
	if (this.$shippingOptions.is(':visible')) {
		this.showScreen('shipping-option-chosen', 'fadeOut');
	} else {
		this.showScreen('choosing-shipping-option');
	}
};

/**
 * Called once a shipping option has been selected. Updates the UI and informs
 * Web Store of the choice.
 */
ConfirmationShippingEstimator.prototype.selectedShippingOption = function(selectedOption) {
	this.selectedProviderId = $(selectedOption).attr('data-provider-id');
	this.selectedPriorityLabel = $(selectedOption).attr('data-priority-label');

	this.selectShippingOption(this.selectedProviderId, this.selectedPriorityLabel);
	this.updateEstimates();

	// Tell Web Store that this shipping option has been selected.
	$.post(
		this.setShippingOptionEndpoint,
		{
			'CheckoutForm[shippingProviderId]': $(selectedOption).attr('data-provider-id'),
			'CheckoutForm[shippingPriorityLabel]': $(selectedOption).attr('data-priority-label')
		}
	);
};

/**
 * Ensure that the shipping option associated with the provided provideId and
 * priorityLabel is checked in the UI.
 * @param int providerId The ID for shipping provider.
 * @param string priorityLabel The label for the shipping priority.
 */
ConfirmationShippingEstimator.prototype.selectShippingOption = function(providerId, priorityLabel) {
	if (providerId === null || priorityLabel === null) {
		// Select the first shipping option.
		this.$shippingOptions.each(
			function (idx, option) {
				$(option).find('input').first().prop('checked', true);
			});

		return;
	}

	var didSelectSomething = false;
	this.$shippingOptions.find('input').each(function (inputIdx, input) {
		// Ensure the input is actually a shipping option.
		// TODO: Investigate why the "Done" button requires an input.
		if (typeof $(input).attr('data-provider-id') === 'undefined' ||
			typeof $(input).attr('data-priority-label') === 'undefined'
		) {
			return;
		}

		if ($(input).attr('data-provider-id') === providerId.toString() &&
			$(input).attr('data-priority-label') === priorityLabel.toString()
		) {
			didSelectSomething = true;
			$(input).prop('checked', true);
		}
	});
};

/**
 * Redraw the shipping options tooltip based on a shippingOptions response from
 * Web Store.
 * @param {array} shippingOptions The shipping options returned by Web Store.
 */
ConfirmationShippingEstimator.prototype.redrawShippingOptions = function(shippingOptions) {
	if ($.isArray(shippingOptions) === false) {
		throw new Error('shippingOptions must be an array.');
	}

	// Clear out the existing tooltip contents.
	this.$shippingOptions.find('ol').html('');

	for (var i = 0, len = shippingOptions.length; i < len; i += 1) {
		this.addShippingOption(shippingOptions[i]);
	}

	this.selectShippingOption(this.selectedProviderId, this.selectedPriorityLabel);

	this.updateEstimates();
};

/**
 * Return the selected shipping option.
 * @return {jQuery selector} The selected shipping option.
 */
ConfirmationShippingEstimator.prototype.getSelectedShippingOption = function() {
	return this.$shippingOptions.find(':checked');
};

/**
 * Update the estimate lines based on the selected shipping option.
 */
ConfirmationShippingEstimator.prototype.updateEstimates = function() {
	if (this.shippingOptions.length === 0) {
		return;
	}

	var selectionData = this.getShippingOption();

	if (typeof(selectionData) === 'undefined') {
		// Let's try to figure out if there was a selected option that we
		// can get back
		if (this.shippingOptions.selectedShippingOption !== null)	{
			selectionData = this.shippingOptions.selectedShippingOption;
		}	else	{
			return;
		}

	}

	this.$shippingEstimate.html(selectionData.formattedShippingPrice);
	this.$taxEstimate.html(selectionData.formattedCartTax);
	this.$tax1Estimate.html(selectionData.formattedCartTax1);
	this.$tax2Estimate.html(selectionData.formattedCartTax2);
	this.$tax3Estimate.html(selectionData.formattedCartTax3);
	this.$tax4Estimate.html(selectionData.formattedCartTax4);
	this.$tax5Estimate.html(selectionData.formattedCartTax5);

	this.$totalEstimate.html(selectionData.formattedCartTotal);
	this.$totalEstimate.css('font-size', this.cacheTotalEstimateFontSize);
};

ConfirmationShippingEstimator.prototype.getShippingOption = function() {
	var option = $.grep(
		this.shippingOptions.shippingOptions,
		function(el) {
			return (
				parseInt(el.providerId) === parseInt(this.selectedProviderId) &&
				el.priorityLabel === this.selectedPriorityLabel
			);
		}.bind(this));

	return option[0];
};

/**
 * After selecting a shipping country, fired from the DOM.
 * @param element {DOM element} The <select> element that fired the onChange
 * event.
 */
ConfirmationShippingEstimator.prototype.selectedCountry = function(element) {
	this.setSelectedCountry($(element).find(':selected'));
	this.showScreen('entering-postal');
};

/**
 * Set the value of the city and state link.
 * @param {string} city The city.
 * @param {string} state The state code.
 */
ConfirmationShippingEstimator.prototype.setCityStateLinkValue = function(city, state) {
	var values = [];

	if (city !== null)
	{
		values.push(city);
	}

	if (state !== null)
	{
		values.push(state);
	}

	this.$shippingCityStateLink.html(values.join(', '));
};

/**
 * Handle any messages that the server has sent us.
 * @param object[] messages An array of messages. Each message is an object with the following properties:
 *	   code string A severity, either WARN or INFO.
 *	   message string The message to display to the user.
 */
ConfirmationShippingEstimator.prototype.handleMessages = function(messages) {
	var message;

	for (var i = 0, len = messages.length; i < len; i += 1) {
		message = messages[i];

		switch (message.code) {
			case 'WARN':
				window.location = this.redirectToShippingOptionsUrl;
				break;
		}
	}
};

/**
 * Add a message to the bottom of the shipping options tooltip.
 * @param string messageText The message to add.
 */
ConfirmationShippingEstimator.prototype.addShippingOptionsBottomMessage = function(messageText) {
	this.$shippingOptions.find('ol').append(
		$('<li>')
			.addClass('webstore-shipping-choices-more')
			.html(messageText)
	);
};

/**
 * Add a message to the top of the shipping options tooltip.
 * @param string messageText The message to add.
 */
ConfirmationShippingEstimator.prototype.addShippingOptionsTopMessage = function(messageText) {
	this.$shippingOptions.find('ol').prepend(
		$('<li>')
			.addClass('webstore-shipping-choices-notice')
			.html(messageText)
	);
};

ConfirmationShippingEstimator.prototype.getPostal = function() {
	return this.$shippingPostalInput.val();
};

/**
 * Update shipping estimates based on the selected country and entered postal
 * code. If no postal code has been entered, then an attempt will still be made
 * to update the estimates. This is valid since in-store pickup does not
 * require a shipping address.
 *
 * @return {jQuery promise} Returns a promise that is resolved when the
 * estimates come back from Web Store.
 */
ConfirmationShippingEstimator.prototype.updateShippingEstimates = function() {
	this.toggleShowCalculatingOnFields();

	// This deferred is what's returned by this function.
	var deferred = $.Deferred();

	$.post(
		this.getShippingRatesEndpoint
	).done(function (shippingRatesResponse) {
		if (typeof shippingRatesResponse.result === 'undefined' ||
			shippingRatesResponse.result !== 'success'
			) {
			// TODO: We have no way to handle an error here. See WS-2076 for a
			// question aimed at Luke about how to display errors.
			deferred.reject();
			return;
		}

		var options = shippingRatesResponse.wsShippingEstimatorOptions;

		this.shippingOptions = options;
		this.handleMessages(options.messages);
		this.selectedProviderId = options.selectedProviderId || null;
		this.selectedPriorityLabel = options.selectedPriorityLabel || null;
		this.updateEstimates();
		deferred.resolve();

	}.bind(this));

	return deferred.promise();
};

/**
 * Displays the loading spinner and removes the calculate label on
 * the button
 * @return {undefined}
 */
ConfirmationShippingEstimator.prototype.toggleLoadingSpinner = function() {
	if (this.$shippingCalculateButton.find('.fa-circle-o-notch').length > 1)
	{
		// we need to hide the spinner
		this.$shippingCalculateButton.html(strCalculateButton);
		this.$shippingCalculateButton.prop('disabled', false);
	}
	else
	{
		this.$shippingCalculateButton.html('<i class="fa fa-circle-o-notch fa-spin fa-lg"></i>');
		this.$shippingCalculateButton.prop('disabled','disabled');
	}
};

/**
 * Displays 'Calculating...' on fields that are expected to change after
 * a recalculation of the shipping estimator's price
 * @return {undefined}
 */
ConfirmationShippingEstimator.prototype.toggleShowCalculatingOnFields = function() {
	$.each(this.calculatingFields, function(idx, el) {
		el.html(calculatingLabel);
	});

	this.$totalEstimate.css('font-size', '0.90rem');
};

/**
 * Called by PromoCodeInput when promocode has been applied or removed.
 * @param string result The result of applying the promocode. One of:
 * 'alert' - display an alert,
 * 'error' -  an error occurred,
 * 'success' - the promocode was applied or removed successfully,
 * 'triggerCalc' - a free shipping promo code was applied or removed successfully.
 */
ConfirmationShippingEstimator.prototype.promoCodeChange = function (result) {
	// TODO: If we could redraw the shipping method name in JavaScript then we
	// could change this to only redirect on removal of promo code instead of
	// both remove and apply.
	if (result === 'triggerCalc') {
		window.location = this.redirectToShippingOptionsUrl;
	}
};
