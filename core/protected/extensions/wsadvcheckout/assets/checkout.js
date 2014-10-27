'use strict';
/* globals alert: false, $: false, cart: false, hideModal: false */

/**
 * @class Checkout
 * @classdesc Handles the shopping cart throughout the checkout process
 */
function Checkout (options) {
	this.applyButtonLabel = options.applyButtonLabel || null;
	this.removeButtonLabel = options.removeButtonLabel || null;

	if (this.applyButtonLabel === null || this.removeButtonLabel === null)	{
		throw new Error('Translation missing for promo code button');
	}
}

/**
 * Toggles the activation of the promo code discount based on the
 * class promocode-applied class on button
 * @param cartId the id of cart section in the DOM
 * @return {undefined}
 */
Checkout.prototype.ajaxTogglePromoCode = function(cartId) {
	if ($('.promocode-apply').hasClass('promocode-applied')) {
		this.ajaxRemovePromoCode(cartId);
	} else {
		this.ajaxApplyPromoCode(cartId);
	}
};

/**
 * Applies the promo code to the cart. Makes a call to the back end
 * to verify that the promo code is valid and update the cart with
 * the right prices.
 * @param cartId the id of cart section in the DOM
 * @return {undefined}
 */
Checkout.prototype.ajaxApplyPromoCode = function (cartId) {
	var promoCodeValue = $('#' + cartId).val();
	$('#' + cartId + '_em_').hide();

	$.ajax({
		data: {
			promoCode: promoCodeValue
		},
		type: 'POST',
		url: '/cart/modalapplypromocode',
		dataType: 'json',
		success: function(data) {
			if (data.action === 'alert') {
				alert(data.errormsg);
			} else if (data.action === 'error') {
				$('#' + cartId + '_em_')
					.find('p')
					.text(data.errormsg)
					.end()
					.hide()
					.fadeIn();
			} else if (data.action === 'triggerCalc') {
				alert(data.errormsg);
			} else if (data.action === 'success') {
				var shoppingCart = JSON.parse(data.shoppingCart);
				$('.webstore-promo-line').show();
				var $this = $('.promocode-apply');
				$this.addClass('promocode-applied');
				$this.html(this.removeButtonLabel);
				this.redrawCart(shoppingCart, cartId);
				$('#' + cartId).prop('readonly', true);
				$('.promo-code-value').prop('value', promoCodeValue);
				// Remove the promo and discount line when the cart is empty.
				if (shoppingCart.cartItems.length === 0) {
					this.ajaxRemovePromoCode(cartId);
					$('.webstore-promo-line').remove();
				}

			}
		}.bind(this)
	});
};

/**
 * Remove the promo code that was applied to the cart
 * @param cartId the id of cart section in the DOM
 * @return {undefined}
 */
Checkout.prototype.ajaxRemovePromoCode = function (cartId) {
	$.ajax({
		type: 'POST',
		url: '/cart/modalremovepromocode',
		dataType: 'json',
		success: function(data) {
			this.clearPromoCode(cartId);
			this.redrawCart(JSON.parse(data.shoppingCart), cartId);
		}.bind(this)
	});
};

/**
 * Toggle the promo code if the 'enter' key is pressed
 * @param event keydown on the input
 * @param cartId the id of cart section in the DOM
 * @return {undefined}
 */
Checkout.prototype.ajaxTogglePromoCodeEnterKey = function(e, cartId){
	if (e.keyCode === 13) {
		this.ajaxTogglePromoCode(cartId);
		e.preventDefault();
	}
};

/**
 * Renders the cart with the new information picked up from the backend
 * after applying a promo discount (tax total, subtotal, total, promo value)
 * @param shoppingCart data that we get back from the shopping cart model as json
 * @return {undefined}
 */
Checkout.prototype.redrawCart = function(shoppingCart, cartId) {
	var rowBaseId = 'cart_row_',
		cartHasDiscount = false;

	if (shoppingCart.cartItems === undefined) {
		throw new Error('shoppingCart.cartItems must be defined');
	}

	for (var itemIdx=0, numItems = shoppingCart.cartItems.length; itemIdx < numItems; itemIdx += 1) {
		var cartItem = shoppingCart.cartItems[itemIdx];
		var row = $('#' + rowBaseId + cartItem.id);
		if (row.length === 0) {
			// Something went wrong, the row being updated was not found in the HTML.
			continue;
		}

		// If a discount has been applied upgrade the unit price to reflect the
		// new price, old price will appear strike through.
		var unitHTML = '';

		if (cartItem.discount !== '0'){
			cartHasDiscount = true;
			unitHTML = '<strike>' + cartItem.sellFormatted + 'ea' + '</strike>' + ' ';
			unitHTML += cartItem.sellDiscountFormatted + 'ea'+ ' ';
		} else {
			unitHTML = cartItem.sellFormatted + 'ea' + ' ';
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

	$('.cart-subtotal').html(shoppingCart.subtotalFormatted);
	$('.shipping-estimate').html(shoppingCart.formattedShippingPrice);
	$('.total-estimate').html(shoppingCart.totalFormatted);

	// Taxes
	$('.tax1-estimate').html(shoppingCart.formattedCartTax1);
	$('.tax2-estimate').html(shoppingCart.formattedCartTax2);
	$('.tax3-estimate').html(shoppingCart.formattedCartTax3);
	$('.tax4-estimate').html(shoppingCart.formattedCartTax4);
	$('.tax5-estimate').html(shoppingCart.formattedCartTax5);
	$('.tax-estimate').html(shoppingCart.taxTotalFormatted);

	// If any kind of discount is applied in the cart, return its total in
	// dollars in the total section.
	if (cartHasDiscount) {
		$('.webstore-promo-line').removeClass('hide-me');
	}

	// If valid promo code was applied display its name in the total section.
	if (typeof shoppingCart.promoCode === 'string' && shoppingCart.promoCode !== '' ) {
		$('.webstore-promo-line').removeClass('hide-me');
		$('.promo-code-name').html(shoppingCart.promoCode);
		$('.promo-code-str').html(shoppingCart.totalDiscountFormatted);
	}

	// if purchase is below the minimum amount and promo code was applied, clear the promo code
	if (shoppingCart.promoCode === null && $('.promocode-apply').hasClass('promocode-applied')) {
		this.clearPromoCode(cartId);
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
		url: '/cart/clearcart',
		dataType: 'json',
		success: function(data) {
			if (data.action === 'alert') {
				alert(data.errormsg);
			} else if (data.action === 'success') {
				return;
			}}
	});
};


Checkout.prototype.clearPromoCode = function(cartId) {
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
	var target = $('#' + targetId);
	var targetOffset = target.offset();
	$('body').append('<div class=\'alert-tooltip\'>' + message + '</div>');
	var tooltip = $('.alert-tooltip');
	tooltip.offset({top: targetOffset.top - tooltip.height() / 2 - 50, left: targetOffset.left - tooltip.width() / 2});
	setTimeout(function() {
		$('.alert-tooltip').fadeOut(500, function() {$(this).remove();});
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
	this.setShippingOptionEndpoint = '/cart/chooseshippingoption';
	this.$root = $(options.class);
	this.$shippingProviderId = $('.shipping-provider-id');
	this.$shippingPriorityLabel = $('.shipping-priority-label');

	this.rates = options.rates;
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
	this.providerId = DOMElement.dataset.providerId || null;
	this.priorityLabel = DOMElement.dataset.priorityLabel || null;

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
		len = this.rates.length;

	for (var i = 0; i < len; i += 1) {
		if (this.rates[i].providerId === parseInt(this.providerId) &&
			this.rates[i].priorityLabel === this.priorityLabel
			) {
			selectedShippingRate = this.rates[i];
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


$(document).on('click', function() {
	if (Checkout.creatingTooltip === false){

		$('.alert-tooltip').fadeOut(500, function(){$(this).remove();});
	}
	Checkout.creatingTooltip = false;
});

$(document).on('click', '#cart .exit, #cart .continue',function()  {
	hideModal();
});

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
		$('.card-logo img').removeClass('active');
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
