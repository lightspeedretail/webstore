'use strict';
/* globals $ */
//TODO: Shipping estimator needs to be updated to take into account the case where there are no items in the cart, shipping should be 0.
/**
 * The WsShippingEstimator class handles interaction with the shipping
 * estimator and tooltip showing the shipping options.
 */
function WsShippingEstimator(options) {
  this.getShippingRatesEndpoint = '/cart/getshippingrates';
  this.setShippingOptionEndpoint = '/cart/chooseshippingoption';

  // Options from the invoking code.
  options = options || {};
  this.class = options.class || null;
  this.shippingOptions = options.shippingOptions || null;
  this.selectedProviderId = options.selectedProviderId || null;
  this.selectedPriorityLabel = options.selectedPriorityLabel || null;
  this.selectedCountryName = options.shippingCountryName || null;
  this.selectedCountryCode = options.shippingCountryCode || null;
  this.shippingCity = options.shippingCity || null;
  this.shippingState = options.shippingState || null;
  this.messages = options.messages || null;
  this.updateOnLoad = options.updateOnLoad || false;

  if (typeof this.class !== 'string') {
    throw new Error('Must provide a string value for the class option.');
  }

  this.$root = $('.' + this.class);
  if (this.$root.length === 0) {
    throw new Error('Unable to find element on page with class=' + this.class);
  }

  // jQuery selectors.
  this.$estimateShippingAndTaxesLink = this.$root.find('.estimate-shipping-and-taxes-link');
  this.$shippingPostalEntry = this.$root.find('.shipping-postal-entry');
  this.$shippingPostalInput = this.$shippingPostalEntry.find('.shipping-postal-input');
  this.$shippingCalculateButton = this.$shippingPostalEntry.find('button');
  this.$shippingCountryPicker = this.$root.find('.shipping-country-picker');

  this.$selectedCountryLink = this.$root.find('.shipping-country-link');

  this.$shippingOptions = this.$root.filter('.shipping-options');
  this.$closeShippingOptions = this.$shippingOptions.find('.close-shipping-options');

  this.$shippingEstimateLine = this.$root.filter('.shipping-estimate-line');
  this.$shippingPostalLink = this.$shippingEstimateLine.find('.shipping-postal-link');
  this.$shippingEstimate = this.$shippingEstimateLine.find('.shipping-estimate');

  this.$taxEstimateLine = this.$root.filter('.tax-estimate-line');
  this.$taxEstimate = this.$taxEstimateLine.find('.tax-estimate');
  this.$shippingCityStateLink = this.$taxEstimateLine.find('.shipping-city-state-link');

  this.$totalEstimate = $('.wsshippingestimator-total-estimate');

  // These are the various possible states for each of the UI elements.
  this.screens = {
    // The initial state before any estimates are available.
    'no-estimates': {
      'show': [
        this.$estimateShippingAndTaxesLink
      ],
      'hide': [
        this.$shippingPostalEntry,
        this.$shippingCountryPicker,
        this.$selectedCountryLink,
        this.$shippingOptions,
        this.$taxEstimateLine,
        this.$shippingEstimateLine,
      ]
    },
    'entering-postal': {
      'show': [
        this.$estimateShippingAndTaxesLink,
        this.$selectedCountryLink,
        this.$shippingPostalEntry,
      ],
      'hide': [
        this.$shippingCountryPicker,
        this.$shippingOptions,
        this.$taxEstimateLine,
        this.$shippingEstimateLine,
      ]
    },
    'choosing-country': {
      'show': [
        this.$estimateShippingAndTaxesLink,
        this.$selectedCountryLink,
        this.$shippingCountryPicker,
      ],
      'hide': [
        this.$shippingPostalEntry,
        this.$shippingOptions,
        this.$taxEstimateLine,
        this.$shippingEstimateLine,
      ]
    },
    'choosing-shipping-option': {
      'show': [
        this.$shippingEstimateLine,
        this.$taxEstimateLine,
        this.$shippingOptions,
      ],
      'hide': [
        this.$shippingPostalEntry,
        this.$shippingCountryPicker,
        this.$selectedCountryLink,
        this.$estimateShippingAndTaxesLink
      ]
    },
    'shipping-option-chosen': {
      'show': [
        this.$shippingEstimateLine,
        this.$taxEstimateLine,
      ],
      'hide': [
        this.$shippingOptions,
        this.$shippingPostalEntry,
        this.$shippingCountryPicker,
        this.$selectedCountryLink,
        this.$estimateShippingAndTaxesLink
      ]
    }
  };

  // When a user enters their postal code, update the postal code link.
  this.$shippingPostalInput.blur(function (e) {
    this.$shippingPostalLink.html(e.target.value);

    // this seems like a no-op but our data can be edited from both the add to
    // cart and edit cart forms.
    this.$shippingPostalInput.val(e.target.value);
  }.bind(this));

  // If shipping options are provided then the user has previously made a
  // shipping choice.
  if (this.shippingOptions !== null) {
    this.redrawShippingOptions(this.shippingOptions);
    this.showScreen('shipping-option-chosen');
  }

  if (this.messages !== null) {
    this.handleMessages(this.messages);
  }

  this.setCityStateLinkValue(this.shippingCity, this.shippingState);

  if (this.updateOnLoad === true) {
    this.calculateShippingEstimates();
  }
}

/**
 * Set which set of UI elements are displayed.
 * @param {string} screeId The ID of the screen in this.screens.
 * @param {string} [hideFunction=hide] The function to call on the jQuery selector.
 */
WsShippingEstimator.prototype.showScreen = function(screenId, hideFunction) {
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
WsShippingEstimator.prototype.setSelectedCountry = function(option) {
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
WsShippingEstimator.prototype.addShippingOption = function(shippingOption) {
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
WsShippingEstimator.prototype.toggleShowShippingOptions = function() {
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
WsShippingEstimator.prototype.selectedShippingOption = function(selectedOption) {
  this.selectedProviderId = selectedOption.dataset.providerId;
  this.selectedPriorityLabel = selectedOption.dataset.priorityLabel;

  this.selectShippingOption(this.selectedProviderId, this.selectedPriorityLabel);
  this.updateEstimates();

  // Tell Web Store that this shipping option has been selected.
  $.post(
    this.setShippingOptionEndpoint,
    {
      'CheckoutForm[shippingProviderId]': selectedOption.dataset.providerId,
      'CheckoutForm[shippingPriorityLabel]': selectedOption.dataset.priorityLabel
    }
  );
};

/**
 * Ensure that the shipping option associated with the provided provideId and
 * priorityLabel is checked in the UI.
 * @param int providerId The ID for shipping provider.
 * @param string priorityLabel The label for the shipping priority.
 */
WsShippingEstimator.prototype.selectShippingOption = function(providerId, priorityLabel) {
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
    if (input.dataset.providerId === providerId.toString() &&
      input.dataset.priorityLabel === priorityLabel.toString()
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
WsShippingEstimator.prototype.redrawShippingOptions = function(shippingOptions) {
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
WsShippingEstimator.prototype.getSelectedShippingOption = function() {
  return this.$shippingOptions.find(':checked');
};

/**
 * Update the estimate lines based on the selected shipping option.
 */
WsShippingEstimator.prototype.updateEstimates = function() {
  var selectedShippingOption = this.getSelectedShippingOption();
  if (selectedShippingOption.length === 0) {
    return;
  }

  var selectionData = this.getSelectedShippingOption().data();

  this.$shippingEstimate.html(selectionData.formattedShippingPrice);
  this.$taxEstimate.html(selectionData.formattedCartTax);
  this.$totalEstimate.html(selectionData.formattedCartTotal);
};

/**
 * After selecting a shipping country, fired from the DOM.
 * @param element {DOM element} The <select> element that fired the onChange
 * event.
 */
WsShippingEstimator.prototype.selectedCountry = function (element) {
  this.setSelectedCountry($(element).find(':selected'));
  this.showScreen('entering-postal');
};

/**
 * Set the value of the city and state link.
 * @param {string} city The city.
 * @param {string} state The state code.
 */
WsShippingEstimator.prototype.setCityStateLinkValue = function(city, state) {
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
 *     code string A severity, either WARN or INFO.
 *     message string The message to display to the user.
 */
WsShippingEstimator.prototype.handleMessages = function(messages) {
  var message;

  for (var i = 0, len = messages.length; i < len; i += 1) {
    message = messages[i];

    switch (message.code) {
      case 'WARN':
        this.addShippingOptionsTopMessage(message.message);
        this.showScreen('choosing-shipping-option');
        break;
      case 'INFO':
        this.addShippingOptionsBottomMessage(message.message);
        break;
    }
  }
};

/**
 * Add a message to the bottom of the shipping options tooltip.
 * @param string messageText The message to add.
 */
WsShippingEstimator.prototype.addShippingOptionsBottomMessage = function(messageText) {
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
WsShippingEstimator.prototype.addShippingOptionsTopMessage = function(messageText) {
  this.$shippingOptions.find('ol').prepend(
    $('<li>')
      .addClass('webstore-shipping-choices-notice')
      .html(messageText)
  );
};

WsShippingEstimator.prototype.getPostal = function() {
  return this.$shippingPostalInput.val();
};

/**
 * Retrieve shipping estimates and show them in a panel.
 * @return {jQuery promise} Returns a promise that is resolved when the
 * estimates come back from Web Store.
 */
WsShippingEstimator.prototype.calculateShippingEstimates = function () {
  var zippoPostal = this.getPostal();

  if (zippoPostal === '') {
    // Cannot get shipping estimates without a postal code.
    return;
  }

  // TODO: This was copied from wsadvcheckout/assets/shipping.js and should be
  // moved into a shared JavaScript file.
  switch (this.selectedCountryCode) {
      // for Great Britain and Canada, Zippopotam only uses the first 3 characters in the
      // query URL. In case someone pastes in the full postal, we account for it here
      case 'GB':
      case 'CA':
        zippoPostal = zippoPostal.substring(0, 3);
      break;
  }
  // TODO End copied block.

  // This deferred is what's returned by this function.
  var deferred = $.Deferred();

  // TODO: Show "Calculating" in Order Total, Shipping and Tax estimates - WS-2745

  var uri = this.selectedCountryCode + '/' + zippoPostal;
  $.ajax({
    url: 'http://api.zippopotam.us/' + uri,
    type: 'GET',
    datatype: 'json',
    crossDomain: true
  }).always(function (placeData) {
    var city = null,
      stateCode = null;
    if (placeData.places !== undefined) {
      // Just use the first place in the response.
      // TODO this doesn't work too well for all countries. For England, the
      // 'place name' can be quite a few miles away and for England and the
      // 'state abbreviation' is ENG.
      city = placeData.places[0]['place name'];
      stateCode = placeData.places[0]['state abbreviation'];
      this.setCityStateLinkValue(city, stateCode);
    } else {
      // An error occurred in the hippo lookup.
      // Probably a "404 not found" because the postcode and country
      // combination isn't valid (according to hippo).
      this.setCityStateLinkValue(null, null);
    }

    $.post(
      this.getShippingRatesEndpoint,
      {
        'CheckoutForm[shippingCountryCode]': this.selectedCountryCode,
        'CheckoutForm[shippingCity]': city,
        'CheckoutForm[shippingState]': stateCode,
        'CheckoutForm[shippingPostal]': this.getPostal()
      }).done(function (shippingRatesResponse) {
        if (typeof shippingRatesResponse.result === 'undefined' ||
            shippingRatesResponse.result !== 'success'
        ) {
          // TODO: We have no way to handle an error here. See WS-2076 for a
          // question aimed at Luke about how to display errors.
          deferred.reject();
          return;
        }

        var options = shippingRatesResponse.wsShippingEstimatorOptions;
        this.redrawShippingOptions(options.shippingOptions);
        this.handleMessages(options.messages);
        this.selectedProviderId = options.selectedProviderId || null;
        this.selectedPriorityLabel = options.selectedPriorityLabel || null;
        this.selectShippingOption(this.selectedProviderId, this.selectedPriorityLabel);
        this.updateEstimates();
        deferred.resolve();

      }.bind(this));
  }.bind(this));

  return deferred.promise();
};

/**
 * Displays the loading spinner and removes the calculate label on
 * the button
 * @return {undefined}
 */
WsShippingEstimator.prototype.toggleLoadingSpinner = function() {
  if (this.$shippingCalculateButton.find('.fa-circle-o-notch').length > 1)
  {
    // we need to hide the spinner
    this.$shippingCalculateButton.html(strCalculateButton);
    this.$shippingCalculateButton.prop('disabled', false);
  }
  else
  {
    this.$shippingCalculateButton.html("<i class='fa fa-circle-o-notch fa-spin fa-lg'></i>");
    this.$shippingCalculateButton.prop('disabled','disabled');
  }

}
