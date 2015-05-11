'use strict';
/* globals alert: false, $: false, cart: false, hideModal: false, advcheckoutTranslation: false */

/**
 * @class Checkout
 * @classdesc Handles the shopping cart throughout the checkout process
 * TODO: Rename this class. The word "checkout" is more likely to be
 * interpreted as the verb than a noun.
 */
function Checkout (options) {
	this.applyButtonLabel = options.applyButtonLabel || null;
	this.removeButtonLabel = options.removeButtonLabel || null;
		
	this.applyPromoCodeEndpoint = options.applyPromoCodeEndpoint;
	this.removePromoCodeEndpoint = options.removePromoCodeEndpoint;
	this.clearCartEndpoint = options.ClearCartEndpoint;

	if (this.applyButtonLabel === null || this.removeButtonLabel === null)	{
		throw new Error('Translation missing for promo code button');
	}
}

/**
 * Toggles the activation of the promo code discount based on the
 * class promocode-applied class on button
 * @param {string} cartId The ID of cart section in the DOM.
 * @return {jQuery promise} Resolved after promo code has been toggled.
 */
Checkout.prototype.ajaxTogglePromoCode = function(cartId, updateCartTotals) {
	updateCartTotals = updateCartTotals || false;

	if ($('.promocode-apply').hasClass('promocode-applied')) {
		return this.ajaxRemovePromoCode(cartId, updateCartTotals);
	} else {
		return this.ajaxApplyPromoCode(cartId, updateCartTotals);
	}
};

/**
 * Applies the promo code to the cart. Makes a call to the back end
 * to verify that the promo code is valid and update the cart with
 * the right prices.
 * @param {string} cartId The ID of cart section in the DOM.
 * @return {jQuery promise} Resolved after promo code has been applied.
 * The promise will be resolved with one of: 'alert', 'error', triggerCalc',
 * or 'success'.
 * alert: Something required an alert to the customer.
 * error: An error occurred.
 * success: The promo code was applied.
 * triggerCalc: The promo code was applied and requires a shipping recalculation.
 */
Checkout.prototype.ajaxApplyPromoCode = function (cartId, updateCartTotals) {
	var promoCodeValue = $('#' + cartId).val();

	updateCartTotals = updateCartTotals || false;

	$('#' + cartId + '_em_').hide();

	var result = $.Deferred();

	// Called when a promo code is successfully applied.
	var promoCodeSuccess = function(shoppingCart) {
		this.applyPromoCode(cartId, shoppingCart.promoCode);
		this.redrawCart(shoppingCart, cartId);

		// Remove the promo and discount line when the cart is empty.
		// TODO: Why is this required?
		if (shoppingCart.cartItems.length === 0) {
			this.ajaxRemovePromoCode(cartId);
			$('.webstore-promo-line').remove();
		}
	}.bind(this);

	$.ajax({
		url: this.applyPromoCodeEndpoint,
		data: {
			promoCode: promoCodeValue,
			updateCartTotals: updateCartTotals
		},
		type: 'POST',
		dataType: 'json'
	}).done(function(response) {
		switch (response.applyResult.action) {
			case 'alert':
				alert(response.applyResult.message);
				break;

			case 'error':
				$('#' + cartId + '_em_')
					.find('p')
					.text(response.applyResult.message)
					.end()
					.hide()
					.fadeIn();
				break;

			case 'triggerCalc':
				alert(response.applyResult.message);
				promoCodeSuccess(response.shoppingCart);
				break;

			case 'success':
				promoCodeSuccess(response.shoppingCart);
				break;
		}

		result.resolve(response.applyResult.action);

	}.bind(this)).fail(function () {
		result.reject();
	});

	return result.promise();
};

/**
 * Remove the promo code that was applied to the cart
 * @param {string} cartId The ID of cart section in the DOM.
 * @return {jQuery promise} Resolved after promo code has been applied.
 */
Checkout.prototype.ajaxRemovePromoCode = function (cartId, updateCartTotals) {
	var result = $.Deferred();
	updateCartTotals = updateCartTotals || false;

	$.ajax({
		type: 'POST',
		url: this.removePromoCodeEndpoint,
		dataType: 'json',
		data: {
			updateCartTotals: updateCartTotals
		}
	}).done(function (response) {
		this.removePromoCode(cartId);
		this.redrawCart(response.shoppingCart, cartId);

		// In order to present a consistent interface to the promise we are
		// mocking a back-end 'triggerCalc' response. Since the back end
		// doesn't tell us whether a recalculation is required (unlike when
		// adding a promo code) we have to assume it is.
		result.resolve('triggerCalc');
	}.bind(this)).fail(function () {
		result.reject();
	});

	return result.promise();
};

/**
 * Toggle the promo code if the 'enter' key is pressed
 * @param {event} e Keydown event.
 * @param {string} cartId The ID of cart section in the DOM.
 * @return {jQuery promise} Resolved once promo code has been toggled (or not).
 */
Checkout.prototype.ajaxTogglePromoCodeEnterKey = function(e, cartId, updateCartTotals){
	updateCartTotals = updateCartTotals || false;

	if (e.keyCode === 13) {
		e.preventDefault();
		return this.ajaxTogglePromoCode(cartId, updateCartTotals);
	}

	// Return an immediately rejected promise.
	var deferred = $.Deferred();
	deferred.reject();
	return deferred.promise();
};

/**
 * Renders the cart with the new information picked up from the backend
 * after applying a promo discount (tax total, subtotal, total, promo value)
 * @param {object} shoppingCart The shopping cart object that we get back from
 * the shopping cart model as json.
 * @param {string} cartId The ID of cart section in the DOM.
 * @return {undefined}
 */
Checkout.prototype.redrawCart = function(shoppingCart, cartId) {
	var rowBaseId = 'cart_row_',
		cartHasDiscount = false;

	if (shoppingCart.cartItems === undefined) {
		throw new Error('shoppingCart.cartItems must be defined');
	}

	for (var itemIdx = 0, numItems = shoppingCart.cartItems.length; itemIdx < numItems; itemIdx += 1) {
		var cartItem = shoppingCart.cartItems[itemIdx];
		var row = $('#' + rowBaseId + cartItem.id);
		if (row.length === 0) {
			// Something went wrong, the row being updated was not found in the HTML.
			continue;
		}

		// If a discount has been applied upgrade the unit price to reflect the
		// new price, old price will appear strike through.
		var unitHTML = '';

		if (parseFloat(cartItem.discount) > 0){
			cartHasDiscount = true;
			unitHTML = '<strike>' + cartItem.sellFormatted + ' ' + advcheckoutTranslation.EACH_SUFFIX + '</strike>' + ' ';
			unitHTML += cartItem.sellDiscountFormatted + ' ' + advcheckoutTranslation.EACH_SUFFIX + ' ';
		} else {
			unitHTML = cartItem.sellFormatted + ' ' + advcheckoutTranslation.EACH_SUFFIX + ' ';
		}

		$(row).find('.price').html(unitHTML);
		$(row).find('.subtotal').html(cartItem.sellTotalFormatted);

		var id = '#CartItem_qty_'+ cartItem.id;
		$(id).val(cartItem.qty);
	}

	// Loop through the table if an item's qty = 0 the item no longer
	// exists in the shoppingCart JSON hence remove the corresponding row.
	$('#user-grid table tbody tr').each(function(index, element) {
		var rowId = $(element).attr('id');
		var found = false;
		for (var i = 0; i < shoppingCart.cartItems.length; i += 1) {
			if (rowBaseId + shoppingCart.cartItems[i].id === rowId) {
				found = true;
				break;
			}
		}
		if (found === false) {
			$('#' + rowId).addClass('delete');
			setTimeout(function() { $('#' + rowId).remove();},500);
		}
	});

	$('.cart-subtotal').html(shoppingCart.formattedCartSubtotal);
	$('.shipping-estimate').html(shoppingCart.formattedShippingPrice);
	$('.total-estimate').html(shoppingCart.formattedCartTotal);

	// Taxes
	$('.tax1-estimate').html(shoppingCart.formattedCartTax1);
	$('.tax2-estimate').html(shoppingCart.formattedCartTax2);
	$('.tax3-estimate').html(shoppingCart.formattedCartTax3);
	$('.tax4-estimate').html(shoppingCart.formattedCartTax4);
	$('.tax5-estimate').html(shoppingCart.formattedCartTax5);
	$('.tax-estimate').html(shoppingCart.formattedCartTax);

	// If valid promo code was applied display its name in the total section.
	if (typeof shoppingCart.promoCode === 'string' && shoppingCart.promoCode !== '' ) {
		$('.webstore-promo-line').removeClass('hide-me');
		$('.promo-code-name').html(shoppingCart.promoCode);
		$('.promo-code-str').html(shoppingCart.totalDiscountFormatted);
	}
	else if (cartHasDiscount) {
		// If any kind of discount is applied in the cart, return its total in
		// dollars in the total section.
		$('.webstore-promo-line').removeClass('hide-me');
		$('.promo-code-str').html(shoppingCart.totalDiscountFormatted);
	}
	else
	{
		$('.webstore-promo-line').addClass('hide-me');
		$('.promo-code-str').html(shoppingCart.totalDiscountFormatted);
	}

	// If purchase is below the minimum amount or the last item to which the promo can be applied
	// has been removed from cart, then clear the promo code.
	if (shoppingCart.promoCode === null && $('.promocode-apply').hasClass('promocode-applied')) {
		this.removePromoCode(cartId);
		$('#' + cartId + '_em_')
			.find('p')
			.text(cart.INVALID_PROMOCODE)
			.end()
			.hide()
			.fadeIn();
	}
};

/**
 * Clears the cart
 * @return {undefined}
 */
Checkout.prototype.ajaxClearCart = function () {
	$.ajax({
		data: null,
		type: 'POST',
		url: this.clearCartEndpoint,
		dataType: 'json',
		success: function(data) {
			if (data.action === 'alert') {
				alert(data.errormsg);
			} else if (data.action === 'success') {
				return;
			}
		}
	});
};

/**
 * Applies the promo code to the cart.
 * @param {string} cartId The ID of cart section in the DOM.
 * @param {string} promoCode The promo code.
 */
Checkout.prototype.applyPromoCode = function(cartId, promoCode) {
	var $promoCodeButton = $('.promocode-apply');
	$promoCodeButton.addClass('promocode-applied');
	$promoCodeButton.html(this.removeButtonLabel);

	$('.webstore-promo-line').show();
	$('#' + cartId).val(promoCode);
	$('#' + cartId).prop('readonly', true);
	$('.promo-code-value').prop('value', promoCode);
};

/**
 * Removes the promo code from the cart.
 * @param {string} cartId The ID of cart section in the DOM.
 */
Checkout.prototype.removePromoCode = function(cartId) {
	var $this = $('.promocode-apply');
	$this.removeClass('promocode-applied');
	$('.webstore-promo-line').hide();
	$this.html(cart.PROMOCODE_APPLY);
	$('#' + cartId).val('');
	$('#' + cartId).prop('readonly', false);
};

/**
 * Displays a tooltip when the user try to add a quantity for a product
 * that exceeds the quantity available
 * @param targetId the input to which it should appear on top of
 * @param message the text to display in the tooltip
 * @return {undefined}
 */
Checkout.prototype.createTooltip = function(targetId, message) {
	this.targetId = targetId;
	this.creatingTooltip = true;

	var target = $('#' + targetId),
		targetOffset = target.offset();

	var $tooltip = $('<div class=\'alert-tooltip\'>' + message + '</div>');
	$('body').append($tooltip);

	$tooltip.offset({
		top: targetOffset.top - $tooltip.height() / 2 - 50,
		left: targetOffset.left - $tooltip.width() / 2
	});

	setTimeout(function() {
		$tooltip.fadeOut(
			500,
			function() {
				$(this).remove();
			}
		);
	}, 4000);
};

/**
 * Adjusts the position of the tooltip over the input
 */
Checkout.prototype.adjustPosition = function() {
	var tooltip = $('.alert-tooltip');
	tooltip.remove();
	var targetId = this.targetId;
	var target = $('#' + targetId);
	var targetOffset = target.offset();
	if (targetOffset !== null) {
		tooltip.offset({top: targetOffset.top - tooltip.height() / 2 - 50, left: targetOffset.left - tooltip.width() / 2});
	}
};

// END of Checkout();
/**
 * @class OrderSummary
 * @classdesc Handles updating the order summary on the checkout screens.
 * @param {object} options The class options.
 * @param {object[]} options.rates An array of shipping rates.
 */
function OrderSummary(options) {
	this.setShippingOptionEndpoint = options.setShippingOptionEndpoint;
	this.$root = $(options.class);
	this.$shippingProviderId = $('.shipping-provider-id');
	this.$shippingPriorityLabel = $('.shipping-priority-label');

	this.cartScenarios = options.cartScenarios || [];
	this.providerId = null;
	this.priorityLabel = null;

	// Ensure the required selectors are on the page.
	var requiredSelectorsOnce = [
		this.$root,
		this.$shippingProviderId,
		this.$shippingPriorityLabel
	];

	for (var selectorIdx in requiredSelectorsOnce) {
		if (requiredSelectorsOnce.hasOwnProperty(selectorIdx)) {
			if (requiredSelectorsOnce[selectorIdx].length === 0) {
				throw new Error(
					'Unable to find an element on the page with selector: ' +
						requiredSelectorsOnce[selectorIdx].selector);
			}

			if (requiredSelectorsOnce[selectorIdx].length > 1) {
				throw new Error(
					'Too many elements on the page with selector: ' +
						requiredSelectorsOnce[selectorIdx].selector);
			}
		}
	}
}

/**
 * Called from the DOM when a shipping option is selected.
 * @param {DOMElement} DOMElement A DOM element.
 */
OrderSummary.prototype.optionSelected = function(DOMElement) {
	this.providerId = $(DOMElement).attr('data-provider-id') || null;
	this.priorityLabel = $(DOMElement).attr('data-priority-label') || null;

	if (this.providerId === null || this.priorityLabel === null) {
		throw new Error('Selected option does not have providerId and priorityLabel data- attributes.');
	}

	this.updateOrderSummary();
	this.postShippingChoice();

	this.$shippingProviderId.val(this.providerId);
	this.$shippingPriorityLabel.val(this.priorityLabel);
};

/**
 * Search the shipping rates array for the selected one.
 * @returns {object} The selected shipping rate.
 */
OrderSummary.prototype.getSelectedShippingRate = function() {
	var selectedShippingRate = null,
		len = this.cartScenarios.length;

	for (var i = 0; i < len; i += 1) {
		if (this.cartScenarios[i].providerId === parseInt(this.providerId) &&
			this.cartScenarios[i].priorityLabel === this.priorityLabel
		) {
			selectedShippingRate = this.cartScenarios[i];
		}
	}

	return selectedShippingRate;
};


/**
 * Updates the order summary based on the selected shipping rate.
 */
OrderSummary.prototype.updateOrderSummary = function() {
	var selectedShippingRate = this.getSelectedShippingRate();
	if (this.getSelectedShippingRate() === null) {
		throw new Error('Cannot find a corresponding shipping rate.');
	}

	this.$root.find('.shipping-estimate').html(selectedShippingRate.formattedShippingPrice);
	this.$root.find('.tax1-estimate').html(selectedShippingRate.formattedCartTax1);
	this.$root.find('.tax2-estimate').html(selectedShippingRate.formattedCartTax2);
	this.$root.find('.tax3-estimate').html(selectedShippingRate.formattedCartTax3);
	this.$root.find('.tax4-estimate').html(selectedShippingRate.formattedCartTax4);
	this.$root.find('.tax5-estimate').html(selectedShippingRate.formattedCartTax5);
	this.$root.find('.total-estimate').html(selectedShippingRate.formattedCartTotal);
};

/**
 * Informs web store about the current shipping option choice.
 * TODO: remove duplication between this file and ConfirmationShippingEstimator.js.
 */
OrderSummary.prototype.postShippingChoice = function() {
	if (this.providerId === null || this.priorityLabel === null) {
		throw new Error('Cannot post a shipping choice with null priorityId or providerLabel');
	}

	$.post(
		this.setShippingOptionEndpoint,
		{
			'CheckoutForm[shippingProviderId]': this.providerId,
			'CheckoutForm[shippingPriorityLabel]': this.priorityLabel
		}
	);
};
// END OrderSummary.js

// BEGIN PromoCodeInput

/**
 * @class OrderSummary
 * @classdesc Handles promocode input.
 * @param {object} options The class options.
 *
/* exported PromoCodeInput */
function PromoCodeInput (options) {
	this.checkout = options.checkout || null;

	// On pages which have a promo code input but don't feature a built-in
	// shipping estimator (for example, the checkout pages), we have to request
	// the server for a respond which includes completely updated shopping cart totals.
	//
	// On pages with a shipping estimator, the totals are updated after applying
	// a promo code anyway.
	this.updateCartTotals = options.updateCartTotals || false;

	// This option exists to support the /checkout/shippingoptions page which
	// we aren't currently able to update using the JavaScript when a PromoCode
	// is successfully applied.
	this.reloadPageOnSuccess = options.reloadPageOnSuccess || false;

	// If a wsShippingEstimator object is provided, its estimates will be
	// updated after a promocode is successfully added or removed.
	this.wsShippingEstimator = options.wsShippingEstimator || null;

	if (this.checkout === null) {
		throw new Error('Must provide options.checkout to PromoCodeInput');
	}
}

/**
 * Toggles the activation of the promo code discount based on the
 * class promocode-applied class on button
 * @param {string} cartId The ID of cart section in the DOM.
 */
PromoCodeInput.prototype.togglePromoCode = function(cartId) {
	var promise = this.checkout.ajaxTogglePromoCode(cartId, this.updateCartTotals);
	this.handlePromoCodeChange(promise);
};

/**
 * Toggles the activation of the promo code discount based on the
 * class promocode-applied class on button
 * @param {string} event The DOM event.
 * @param {string} cartId The ID of cart section in the DOM.
 */
PromoCodeInput.prototype.togglePromoCodeEnterKey = function(event, cartId) {
	var promise = this.checkout.ajaxTogglePromoCodeEnterKey(event, cartId, this.updateCartTotals);
	this.handlePromoCodeChange(promise);
};

/**
 * Handles the promise returned by toggling the promo code.
 * @param {jQuery promise} promoCodeChangePromise The promise returned by
 * promocode change methods. @see Checkout.ajaxApplyPromoCode.
 */
PromoCodeInput.prototype.handlePromoCodeChange = function(promoCodeChangePromise) {
	if (this.wsShippingEstimator !== null) {
		promoCodeChangePromise.done(function(result) {
			if (result === 'success' || result === 'triggerCalc') {
				// TODO: For efficiency, we could actually make a
				// lighter-weight call to the backend to simply update totals
				// in the case of 'success'. This would require support from the
				// back end.
				this.wsShippingEstimator.promoCodeChange(result);
			}
		}.bind(this));
	}

	if (this.reloadPageOnSuccess === true) {
		promoCodeChangePromise.done(function(result) {
			if (result === 'triggerCalc' || result === 'success') {
				location.reload();
			}
		}.bind(this));
	}
};
// END PromoCodeInput

// BEGIN WsEditCartModal
/**
 * Provides JavaScript required to make the edit cart functionality work.
 * @param {object} options The class options.
 * @param {string} options.checkoutUrl The url to go to for the checkout.
 * @param {string} options.updateCartItemEndpoint The url to post to for updating the cart.
 * @param {string} options.csrfToken The Yii cross-site request forgery token.
 * @param {string} options.cartId The ID of cart section in the DOM.
 * @param {string} options.invalidQtyMessage The message to display when the quantity is invalid.
 * @param {Checkout} options.checkout An instance of Checkout.
 * @param {WsShippingEstimator} options.wsShippingEstimator An instance
 * WsShippingEstimator or ConfirmationShippingEstimator.
 */
function WsEditCartModal(options) {
	this.checkoutUrl = options.checkoutUrl || null;
	this.updateCartItemEndpoint = options.updateCartItemEndpoint || '';
	this.csrfToken = options.csrfToken || null;
	this.cartId = options.cartId || null;
	this.invalidQtyMessage = options.invalidQtyMessage || null;
	this.checkout = options.checkout || null;
	this.wsShippingEstimator = options.wsShippingEstimator || null;

	// These options are required.
	var requiredOptions = [
		'updateCartItemEndpoint', 'csrfToken', 'cartId', 'invalidQtyMessage'
	];

	requiredOptions.forEach(function (option) {
		if (this[option] === null) {
			throw new Error('Must provide options.' + option);
		}
	}.bind(this));

	// Class state.
	// The number of AJAX requests in progress.
	// We don't want to goToCheckout until all of these have resolved.
	this.requestsInProgress = 0;
}

/**
 * Update the quantity of a line item given an input element on the page
 * containing the new quantity.
 * @param {DOM element} DOMInput The input element in the DOM.
 */
WsEditCartModal.prototype.updateCart = function(DOMInput) {
	this.updateCartItemQty(
		$(DOMInput).attr('data-pk'),
		DOMInput.value
	).done(function(updateResponse) {
		if (updateResponse.updateResult.errorId === 'invalidQuantity') {
			$(DOMInput).val(updateResponse.updateResult.availQty);
			$(DOMInput).change();

			this.checkout.createTooltip(
				DOMInput.id,
				this.invalidQtyMessage.replace('{qty}', updateResponse.updateResult.availQty)
			);
		}
	}.bind(this));
};

/**
 * Remove an item from the cart given an input element on the page.
 * @param {DOM element} DOMInput The input element in the DOM.
 * @param {Event} event The event which fired this function.
 */
WsEditCartModal.prototype.removeItem = function(DOMInput, event) {
	// Prevent the event firing and modifying the browser history.
	event.preventDefault();
	var cartItemId = DOMInput.getAttribute('data-pk');
	this.updateCartItemQty(cartItemId, 0);
};

/**
 * Update the quantity of a line item given the CartItem ID and the new quantity.
 * @param {string} cartItemId The ID of the cart item.
 * @param {integer} qty The new quantity.
 */
WsEditCartModal.prototype.updateCartItemQty = function(cartItemId, qty) {
	if (typeof cartItemId === 'undefined' || typeof qty === 'undefined') {
		throw new Error('Must provide a cartItemId and a qty.');
	}
	this.incrementPendingRequests();
	var returnValue = $.ajax({
		url: this.updateCartItemEndpoint,
		type: 'POST',
		dataType: 'json',
		data: {
			YII_CSRF_TOKEN: this.csrfToken,
			'CartItem[id]': cartItemId,
			'CartItem[qty]': qty
		}
	}).done(function (updateResponse) {
		if (updateResponse.updateResult.action === 'success') {
			this.checkout.redrawCart(updateResponse.shoppingCart, this.cartId);
			$('#cartItemsTotal').text(updateResponse.shoppingCart.cartItems.length);

			// Update the shipping estimate.
			if (this.wsShippingEstimator !== null) {
				this.incrementPendingRequests();

				// Note that we don't handle an outright failure (the promise
				// being rejected); we allow the user to progress to the
				// checkout in that case.
				this.wsShippingEstimator
				.updateShippingEstimates()
				.done(function (shippingRatesResponse) {

					// Check whether to cancel the pendingRequestsComplete
					// callback based on the shipping rates response containing
					// errors or warnings.
					if (typeof this.pendingRequestsComplete === 'function' &&
						this.shippingResponsePreventsCheckout(shippingRatesResponse) === true
					) {
						this.pendingRequestsComplete = null;
					}

				}.bind(this)).always(function () {
					this.decrementPendingRequests();
				}.bind(this));

				return;
			}
		}

		if (updateResponse.updateResult === 'error' && updateResponse.updateResult.errormsg) {
			alert(updateResponse.updateResult.errormsg);
		}
	}.bind(this)).fail(function () {
		// An error occurred. This can happen naturally in Safari when a user
		// clicks on a link before this request has completed. For example,
		// when the user modifies the quantity and clicks "checkout" without
		// clicking outside the input. See WS-3183.
		// TODO: It might be better for the checkout link to wait for any
		// pending requests (such as this one) to complete before processing.
	}.bind(this)).always(function () {
		this.decrementPendingRequests();
	}.bind(this));

	return returnValue;
};

/**
 * Whether the shipping response getting updated shipping rates should prevent
 * the user from being progressed to the checkout. This is used when the user
 * clicks "checkout" and there are pending requests.
 * @param {object} shippingRatesResponse The response returned by the promise
 * from wsShippingEstimator.calculateShippingEstimates().
 * @return {boolean} Whether to prevent checkout.
 */
WsEditCartModal.prototype.shippingResponsePreventsCheckout = function(shippingRatesResponse) {
	var message;

	// Anything but success prevents checkout.
	if (shippingRatesResponse.result !== 'success') {
		return true;
	}

	// Any warning messages prevent checkout.
	var messages = shippingRatesResponse.wsShippingEstimatorOptions.messages;

	if (typeof messages !== 'object') {
		return false;
	}

	for (var i = 0, len = messages.length; i < len; i += 1) {
		message = messages[i];

		if (message.code === 'WARN') {
			return true;
		}
	}

	return false;
};

/**
 * Whether there are any pending requests.
 * @return {boolean}
 */
WsEditCartModal.prototype.hasPendingRequests = function() {
	return (this.requestsInProgress > 0);
};

/**
 * Add one to the number of pending requests.
 */
WsEditCartModal.prototype.incrementPendingRequests = function() {
	this.requestsInProgress += 1;
};

/**
 * Subtract one from the number of pending requests.
 * If all pending requests are complete then the pendingRequestsComplete
 * callback is called (if one exists).
 */
WsEditCartModal.prototype.decrementPendingRequests = function() {
	this.requestsInProgress -= 1;
	if (this.requestsInProgress === 0 && typeof this.pendingRequestsComplete === 'function') {
		this.pendingRequestsComplete();
	}
};

/**
 * Take the user to the checkout.
 * If any requests are pending, we wait until they complete successfully until
 * progressing the user.
 */
WsEditCartModal.prototype.goToCheckout = function() {
	// If there are no pending requests we can go straight to the checkout.
	if (this.hasPendingRequests() === false) {
		window.location.href = this.checkoutUrl;
		return;
	}

	// Otherwise, wait until they complete before going to the checkout.
	this.pendingRequestsComplete = function () {
		window.location.href = this.checkoutUrl;
	};
};
// END WsEditCartModal

// BEGIN CreditCard.js
/* exported CreditCard */
/**
 * @class CreditCard
 * @classdesc Handles credit card validation.
 * @param {object} options The class options.
 * @param {string[]} options.enabledCardTypes An an array of card types that
 * are enabled.
 * @param {string} options.cardTypeNotSupported A string to display when the
 * card type is not supported.
 */
function CreditCard(options) {
	var enabledCardTypes = options.enabledCardTypes,
		cardTypeNotSupported = options.cardTypeNotSupported;

	if (typeof enabledCardTypes === 'undefined' || typeof cardTypeNotSupported === 'undefined') {
	  throw new Error('Must provide enabledCardTypes and cardTypeNotSupported options.');
	}

	// Map from the values returned by jquery.payment.js to the credit card label.
	var cardTypeMap = {
		'visaelectron': 'Visa Electron',
		'maestro': 'Maestro',
		'visa': 'Visa',
		'mastercard': 'MasterCard',
		'amex': 'American Express',
		'dinersclub': 'Diners Club',
		'discover': 'Discover',
		'jcb': 'JCB'
	};

	$('[data-numeric]').payment('restrictNumeric');
	$('.creditcard-number').payment('formatCardNumber');
	$('.cc-exp').payment('formatCardExpiry');
	$('.cvv').payment('formatCardCVC');

	var removeErrorHolder = function() {
		var $errorHolderElement = $('form > .creditcard > .error-holder'),
			$formErrorElement = $errorHolderElement.find('.form-error');

		$formErrorElement.remove();
	};

	var setNotSupportedMessage = function(message) {
		// jQuery selectors for the error holders on the page.
		var $notSupportedElement = $('.credit-card-not-supported-error'),
			$errorHolderElement = $('form > .creditcard > .error-holder'),
			$formErrorElement = $errorHolderElement.find('.form-error');

		if ($notSupportedElement.length === 0) {
			// The page doesn't already contain error holder element. Let's create it.
			if ($formErrorElement.length === 0) {
				// The page doesn't even contain a form-error container.
				$formErrorElement = $('<div class="form-error">').appendTo($errorHolderElement);
			}

			$formErrorElement.append('<p class="credit-card-not-supported-error">' + message + '</p>');
		} else {
			$notSupportedElement.html(message);
		}
	};

	$('.creditcard-number').keyup(function() {
		removeErrorHolder();
		$('.card-logo div').removeClass('active');
		var cardType = $.payment.cardType($('.creditcard-number').val());

		if (cardType === null) {
			// The card number entered is not a recognised card type -
			// remove the "is not supported" message.
			$('.card-type').val('');
			return;
		}

		var mappedCardType = cardTypeMap[cardType] || cardType.toUpperCase;
		$('.card-logo .' + cardType).addClass('active');
		$('.card-type').val(mappedCardType);

		var isEnabled = false;
		for (var idx = 0, numCardTypes = enabledCardTypes.length; idx < numCardTypes; idx += 1) {
			if (mappedCardType === enabledCardTypes[idx]) {
				isEnabled = true;
			}
		}

		if (isEnabled === false) {
			// Card type is disabled - show a "is not supported" message.
			setNotSupportedMessage(cardTypeNotSupported.replace(/{card type}/, mappedCardType));
		}
	});
}
// END CreditCard.js

$(document).on('click', function() {
	if (Checkout.creatingTooltip === false){
		$('.alert-tooltip').fadeOut(500, function(){
			$(this).remove();
		});
	}

	Checkout.creatingTooltip = false;
});
