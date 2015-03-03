/* globals $: false, alert: false */
'use strict';

/**
 * Creates the functions which help with displaying the shipping scenarios on
 * the single page checkout.
 * @class
 *
 * @param {Object} options
 * @param {string} options.calculateShippingEndPoint The endpoint for the
 *        calculate shipping ajax call
 * @param {number[]} options.paymentModulesThatUseCard An array of payment
 *        module IDs that require display of the credit card form.
 * @param {number[]} options.paymentModulesThatUseForms An array of payment
 *        module IDs that require display of a sub form.
 * @param {string} options.shippingProviderId The DOM ID of the container for
 *        the shipping provider options.
 * @param {string} options.shippingPriority The DOM ID of the container for
 *        the shipping priority options.
 * @param {string} options.paymentProviderId The DOM ID of the <select> for the
 *        payment provider.
 * @param {string} options.intShippingAddressName The DOM name shared by the
 *        radio buttons which allow the user to select their address.
 * @param {string} options.shippingAddress1Id The DOM ID of the first shipping
 *        address <input>.
 * @param {string} options.shippingAddress2Id The DOM ID of the second shipping
 *        address <input>.
 * @param {string} options.shippingCityId The DOM ID of the city <input>.
 * @param {string} options.shippingStateId The DOM ID of the state <input>.
 * @param {string} options.shippingPostalId The DOM ID of the postal code
 *        <input>.
 * @param {string} options.promoCode The DOM ID of the promo code <input>.
 * @param {string} options.promoCodeError The DOM ID of the element containing
 *        the promo code error message.
 * @param {string|null} options.savedShippingProviders An HTML snippet
 *        containing radio buttons for the shipping providers.
 * @param {Object|null} options.savedShippingPrices An object where each key is
 *        a shipping module ID and each value is an array of formatted prices.
 *        Each price in the array corresponds to the shipping price for a
 *        shipping priority.
 * @param {Object|null} options.savedTaxes An object where each key is a
 *        shipping module ID and each value is an array of formatted tax prices.
 *        Each tax price in the array corresponds to the taxes on a shipping
 *        priority.
 * @param {Object|null} options.savedTotalScenarios An object where each key is
 *        a shipping module ID and each value is an array of formatted prices.
 *        Each price in the array corresponds to the total cart price when for a
 *        shipping priority.
 * @param {Object|null} options.savedShippingPriorities An object where each key
 *        is a shipping module ID and each value is an HTML snippet containing
 *        radio buttons for the shipping priorities.
 * @param {Object|null} options.savedCartScenarios An object where each key is a
 *        shipping module ID and each value is an HTML snippet containing the
 *        table of cart items (including description, price, quantity).
 * @param {string|null} options.options.pickedShippingProvider The ID of the
 *        chosen shipping provider.
 * @param {string|null} options.options.pickedShippingPriority The ID of the
 *        chosen shipping priority.
 */
var SinglePageCheckout = function (options) {
	this.calculateShippingEndpoint = options.calculateShippingEndpoint;
	this.paymentModulesThatUseCard = options.paymentModulesThatUseCard;
	this.paymentModulesThatUseForms = options.paymentModulesThatUseForms;

	this.shippingProviderId = options.shippingProviderId;
	this.shippingPriorityId = options.shippingPriorityId;
	this.paymentProviderId = options.paymentProviderId;
	this.intShippingAddressName = options.intShippingAddressName;
	this.shippingAddress1Id = options.shippingAddress1Id;
	this.shippingAddress2Id = options.shippingAddress2Id;
	this.shippingCityId = options.shippingCityId;
	this.shippingStateId = options.shippingStateId;
	this.shippingPostalId = options.shippingPostalId;

	this.promoCode = options.promoCode;
	this.promoCodeError = options.promoCodeError;

	this.savedShippingProviders = options.savedShippingProviders;
	this.savedShippingPrices = options.savedShippingPrices;
	this.savedTaxes = options.savedTaxes;
	this.savedTotalScenarios = options.savedTotalScenarios;
	this.savedShippingPriorities = options.savedShippingPriorities;
	this.savedCartScenarios = options.savedCartScenarios;

	// Select the picked shipping option.
	this.pickedShippingProvider = options.pickedShippingProvider;
	this.pickedShippingPriority = options.pickedShippingPriority;

	// jQuery selectors.
	this.$shippingProvider = $('#' + this.shippingProviderId);
	this.$shippingPriority = $('#' + this.shippingPriorityId);
	this.$paymentProvider = $('#' + this.paymentProviderId);
	this.$shippingAddress1 = $('#' + this.shippingAddress1Id);
	this.$shippingAddress2 = $('#' + this.shippingAddress2Id);
	this.$shippingCity = $('#' + this.shippingCityId);
	this.$shippingState = $('#' + this.shippingStateId);
	this.$shippingPostal = $('#' + this.shippingPostalId);
	this.$promoCode = $('#' + this.promoCode);
	this.$promoCodeError = $('#' + this.promoCodeError);

	// Draw the shipping options.
	this.$shippingProvider.html(this.savedShippingProviders);
	this.$shippingPriority.html(this.savedShippingPriorities);

	// A value of -1 means that no priority has been chosen, so we can simply
	// pick the first one.
	if (this.pickedShippingProvider === '-1') {
		this.pickedShippingProvider = this.getFirstShippingProvider();
	}

	this.setSelectedShippingProvider(this.pickedShippingProvider);

	// A value of -1 means that no priority has been chosen, so we can simply
	// pick the first one.
	if (this.pickedShippingPriority === '-1') {
		this.pickedShippingPriority = this.getFirstShippingPriority();
	}

	this.pickShippingPriority(this.pickedShippingPriority);
};

/**
 * Send a request to get the shipping scenarios.
 */
SinglePageCheckout.prototype.calculateShipping = function () {
	$('#shippingSpinner').show();
	this.$shippingProvider.html('');
	this.$shippingPriority.html('');

	var sendCalculateShipping = function () {
		// Remove the ajaxStop event handler.
		// If this function was called directly instead of by a handler,
		// nothing happens.
		$(document).off('ajaxStop');

		$.post(
			this.calculateShippingEndpoint,
			$('#checkout').serialize(),
			this.handleCalculateShippingResponse.bind(this)
		);
	}.bind(this);

	// If there are no pending AJAX requests, we can send this one right away.
	// Otherwise attach to the ajaxStop handler, which is called when all AJAX
	// requests have finished.
	// This prevents a race condition with saving to the session, see
	// https://github.com/yiisoft/yii/issues/339.
	if ($.active === 0) {
		sendCalculateShipping();
	} else {
		$(document).ajaxStop(sendCalculateShipping);
	}
};

/**
 * Handle the response from the calculate shipping request.
 * @param object res The response from Web Store.
 */
SinglePageCheckout.prototype.handleCalculateShippingResponse = function(res) {
	var savedPickedModule = $('#' + this.paymentProviderId).val();

	// Error messages are shown to the user.
	if (typeof res.errormsg === 'string') {
		alert(res.errormsg);
	}

	// If an error occurred we cannot continue.
	if (res.result === 'error') {
		return;
	}

	// A promo code can be removed as a result of getting updated
	// cart scenarios if the tax code has changed and the promo
	// code theshold is no longer met.
	if (res.wasPromoCodeRemoved === true) {
		this.$promoCode.val('');
		this.$promoCodeError.text('');
	}

	this.savedShippingProviders = res.provider;
	this.savedShippingPrices = res.prices;
	this.savedTaxes = res.taxes;
	this.savedTotalScenarios = res.totals;
	this.savedShippingPriorities = res.priority;
	this.savedCartScenarios = res.cartitems;

	this.$shippingProvider.html(res.provider);
	this.$shippingPriority.html(res.priority);
	this.$paymentProvider.html(res.paymentmodules);
	this.$paymentProvider.val(savedPickedModule);

	$('#shippingSpinner').hide();
	$('#shippingProvider_0').click();
};

/**
 * @return string The ID value of the first shipping provider in the list.
 */
SinglePageCheckout.prototype.getFirstShippingProvider = function() {
	return $('#shippingProvider_0').attr('value');
};

/**
 * @return string The ID value of the first shipping priority in the list.
 */
SinglePageCheckout.prototype.getFirstShippingPriority = function() {
	return $('#shippingPriority_0').attr('value');
};

/**
 * @return string The selected shipping address ID. Or 0 if "Or enter a new
 * address" is selected.
* */
SinglePageCheckout.prototype.getSelectedShippingAddressId = function() {
	return $('input[name="' + this.intShippingAddressName + '"]:checked').val();
};

/**
 * Set the selected shipping provider ID.
 * @param string providerId The new shipping provider ID.
 */
SinglePageCheckout.prototype.setSelectedShippingProvider = function(providerId) {
	if (this.savedShippingPriorities === null) {
		return;
	}

	this.$shippingPriority.html(this.savedShippingPriorities[providerId]);
	$('#shippingProvider input[value="' + providerId + '"]').prop('checked', true);
	$('#ytCheckoutForm_shippingProvider').val(providerId);
	this.pickedShippingProvider = providerId;
};

/**
 * Upon selecting a shipping provider.
 * @param string providerId The new shipping provider ID.
 */
SinglePageCheckout.prototype.pickShippingProvider = function(providerId) {
	this.setSelectedShippingProvider(providerId);

	// Select the first one.
	this.pickShippingPriority(this.getFirstShippingPriority());
};

/**
 * Choose a new shipping priority.
 * @param string shippingPriorityId The ID (actually an index) of the shipping priority.
 */
SinglePageCheckout.prototype.pickShippingPriority = function(priorityIdx) {
	// Default to selecting the first one.
	$('#shippingPriority_0[value="' + priorityIdx + '"]').prop('checked', true);
	$('#ytCheckoutForm_shippingPriority').val(null);
	this.pickedShippingPriority = priorityIdx;
	this.updateCart(this.pickedShippingPriority);
};

/**
 * Upon selecting a shipping priority.
 * @param string shippingPriorityId The ID (actually an index) of the shipping priority.
 */
SinglePageCheckout.prototype.updateCart = function(shippingPriorityId) {
	if(shippingPriorityId === undefined) {
		return;
	}

	this.pickedShippingPriority = shippingPriorityId;
	$('#ytCheckoutForm_shippingPriority').val(this.pickedShippingPriority);

	if (this.savedCartScenarios !== null) {
		$('#cartItems').html(
			this.savedCartScenarios[this.pickedShippingProvider]
		);
	}

	if (this.savedShippingPrices !== null) {
		if (this.savedShippingPrices[this.pickedShippingProvider] !== undefined) {
			$('#cartShipping').html(
				this.savedShippingPrices[this.pickedShippingProvider][this.pickedShippingPriority]
			);
		}
	}

	if (this.savedTaxes !== null) {
		if (this.savedTaxes[this.pickedShippingProvider] !== undefined) {
			$('#cartTaxes').html(
				this.savedTaxes[this.pickedShippingProvider][this.pickedShippingPriority]
			);
		}
	}

	if (this.savedTotalScenarios !== null) {
		$('#cartTotal').html(
			this.savedTotalScenarios[this.pickedShippingProvider][this.pickedShippingPriority]
		);
	}
};

/**
 * Update the shipping options. This can be called when something in the
 * address form changes.
 */
SinglePageCheckout.prototype.updateShippingAuto = function() {
	var hasAddress =
		this.getSelectedShippingAddressId() !== '0' ||
		(
			this.$shippingAddress1.val() &&
			this.$shippingCity.val() &&
			this.$shippingState.val() &&
			this.$shippingPostal.val()
		);

	if(hasAddress) {
		$('#btnCalculate').click();
	}
};

/**
 * Update the cart with updated tax data.
 *
 * Some address updates can trigger an update to the cart tax. Sometimes these
 * updates require the shipping options to be recalculated.
 * @param array setTaxResponse The response from requesting cart/settax.
 */
SinglePageCheckout.prototype.updateTax = function(setTaxResponse) {
	$('#cartItems').html(setTaxResponse.cartitems);

	if (setTaxResponse.action === 'triggerCalc')
	{
		$('#btnCalculate').click();
	}
};

/**
 * After selecting a payment method, update the form.
 * @param string paymentModuleId The ID of the payment module.
 */
SinglePageCheckout.prototype.changePayment = function(paymentModuleId) {
	// this.paymentModulesThatUseCard is an array of integers but
	// paymentModuleId is a string. $.inArray does strict comparison.
	// TODO: Change paymentModulesThatUseCard to an array of strings.
	paymentModuleId = parseInt(paymentModuleId, 10);

	if($.inArray(paymentModuleId, this.paymentModulesThatUseCard) > -1) {
		$('#CreditCardForm').show();
	} else {
		$('#CreditCardForm').hide();
	}

	$.each(this.paymentModulesThatUseForms, function(index, value) {
		$('#payform' + value).hide();
	});

	if($.inArray(paymentModuleId, this.paymentModulesThatUseForms) > -1) {
		$('#payform' + paymentModuleId).show();
	}
};
