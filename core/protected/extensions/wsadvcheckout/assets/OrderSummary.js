'use strict';
/* global $ */

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
  this.$root.find('.total-estimate').html(selectedShippingRate.formattedCartTotal);
};

/**
 * Informs web store about the current shipping option choice.
 * TODO: remove duplication between this file and WsShippingEstimator.js.
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
