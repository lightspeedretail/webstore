'use strict';
/* globals $: false, helper: false */

/**
 * @class Address
 * @param options
 * @constructor
 */
function Address (options) {
	this.options = options || {};
	this.helper = helper;
	this.selectedAddress = null;
	this.defaultBillingId = this.options.defaultBillingId;
	this.defaultShippingId = this.options.defaultShippingId;
	this.$addressList = $('ul.address-blocks');
	this.$addressForm = $('div.address-new.edit');
	this.$addressListTitle = $('div.address-info h4.title');
	this.$recipientAddressId = $('#recipient-address-id');
	this.$recipientFirstName = $('#recipient-firstname');
	this.$recipientLastName = $('#recipient-lastname');
	this.$recipientCompanyToggle = $('#recipient-company-toggle');
	this.$fieldContainerToggle = $('.field-container-toggle');
	this.$recipientCompany = $('#recipient-company');
	this.$recipientResidential = $('#recipient-residential');
	this.$recipientAddress1 = $('#recipient-address1');
	this.$recipientAddress2 = $('#recipient-address2');
	this.$recipientZip = $('#recipient-zip');
	this.$recipientCity = $('#recipient-city');
	this.$recipientStateCode = $('#recipient-state-code');
	this.$recipientStateId = $('#recipient-state-id');
	this.$recipientCountry = $('#recipient-country');
	this.$makeDefaultShipping = $('#make-default-shipping');
	this.$makeDefaultBilling = $('#make-default-billing');
	this.$addressFormTitle = $('#address-form-title');
	this.$confirmAddress = $('#confirm-address');
	this.$addAccountAddress = $('.add-account-address');
	this.$addFirstAccountAddress = $('#add-first-account-address');
	this.$editAccountAddress = $('.edit-account-address');
	this.$removeAccountAddress = $('.remove-account-address');
	this.$accountAddressForm = $('#account-address-form');
	this.$cancelAddress = $('#cancel-address');
	this.$fieldContainerNarrowed = $('.field-container-narrowed');
	this.$companyContainer = $('.company-container');
	this.$yiiCsrfToken = $('#yii-csrf-token');
	this.bindEvents();
}

/**
 * Bind events.
 */
Address.prototype.bindEvents = function() {
	this.$editAccountAddress.on('click', this.editAddress.bind(this));
	this.$addAccountAddress.on('click', this.addAddress.bind(this));
	this.$addFirstAccountAddress.on('click', this.addAddress.bind(this));
	this.$removeAccountAddress.on('click', this.removeAddress.bind(this));
	this.$accountAddressForm.submit(this.confirmEditAddress.bind(this));
	this.$cancelAddress.on('click', this.cancelAddress.bind(this));
	this.$recipientCountry.on('change', this.recipientCountryChanged.bind(this));
	this.$recipientCompanyToggle.on('click', this.toggleCompanyInput.bind(this));
};

/**
 * Show the Address form and clear the form and its error messages.
 * @param event jQuery event object
 */
Address.prototype.addAddress = function (event) {

	event.preventDefault();

	this.helper.clearErrors();

	this.$addressList.addClass('flipped');
	this.$addressForm.addClass('flipped');
	this.$addressListTitle.hide();
	this.$addressFormTitle.text(this.options.ADD_ADDRESS);
	this.$confirmAddress.val(this.options.CONFIRM_ADD_ADDRESS);

	this.$recipientAddressId.val('');
	this.$recipientFirstName.val('');
	this.$recipientLastName.val('');
	this.$recipientCompany.val('');
	this.$recipientResidential.prop('checked', false);
	this.$recipientAddress1.val('');
	this.$recipientAddress2.val('');
	this.$recipientZip.val('');
	this.$recipientCity.val('');
	this.$recipientStateCode.val('');
	this.$recipientCountry.val('');
	this.$makeDefaultBilling.prop('checked', false);
	this.$makeDefaultShipping.prop('checked', false);
};

/**
 * Show the Edit Address panel and populate the form with the customers'
 * selected address data.
 * @param event jQuery event object
 */
Address.prototype.editAddress = function (event) {
	var selectedAddressId;
	if(event){
		selectedAddressId = $(event.target).attr('data-address-id');
	}

	$.get(this.options.MY_ACCOUNT_ADDRESS_URL + '?id=' + selectedAddressId,
		this.handleEditAddressResponse.bind(this)
	);
};

/**
 * Populates address form.
 * @param data address data object
 */
Address.prototype.handleEditAddressResponse = function(data) {
	if (data.status === 'error') {
		this.helper.showErrors('.address-new', data.errors);
		return;
	}

	this.selectedAddress = data.address;

	this.helper.clearErrors();

	this.$addressList.addClass('flipped');
	this.$addressForm.addClass('flipped');
	this.$addressListTitle.hide();
	this.$addressFormTitle.text(this.options.EDIT_ADDRESS);
	this.$confirmAddress.val(this.options.CONFIRM_EDIT_ADDRESS);

	this.$recipientAddressId.val(this.selectedAddress.id);
	this.$recipientFirstName.val(this.selectedAddress.first_name);
	this.$recipientLastName.val(this.selectedAddress.last_name);
	this.$recipientCompany.val(this.selectedAddress.company);
	this.$recipientResidential.prop('checked', !!parseInt(this.selectedAddress.residential, 10));
	this.$recipientAddress1.val(this.selectedAddress.address1);
	this.$recipientAddress2.val(this.selectedAddress.address2);
	this.$recipientZip.val(this.selectedAddress.postal);
	this.$recipientCity.val(this.selectedAddress.city);
	this.$recipientCountry.val(this.selectedAddress.country_id);
	this.$makeDefaultBilling.prop('checked', this.selectedAddress.id === this.defaultBillingId);
	this.$makeDefaultShipping.prop('checked', this.selectedAddress.id === this.defaultShippingId);

	this.populateState(this.selectedAddress.country_id);
};

/**
 * Populate state field according to country if country doesn't have a states sets to N/A.
 * @param countryId ID of the country to retrieve states from
 */
Address.prototype.populateState = function(countryId) {
	$.get(this.options.GET_STATES_URL + '?countryId=' + countryId, function (states) {
		var selectedAddressStateId, selectedState;
		// If there are states in the selected country, then populate the state field
		// Otherwise, set the state to "N/A"
		if (states.length > 1) {
			if (this.selectedAddress !== null && this.selectedAddress !== undefined){
				selectedAddressStateId = parseInt(this.selectedAddress.state_id, 10);
				selectedState = this.helper.getArrayElementById(
					states, selectedAddressStateId
				);
				this.$recipientStateCode.val(selectedState.name);
			}
			else{
				this.$recipientStateCode.val('');
			}

			this.$recipientStateCode.prop('disabled', false);
		}
		else if (states.length === 1) {
			this.$recipientStateCode.val(states[0].name.toUpperCase());
			this.$recipientStateCode.prop('disabled', true);
		}
	}.bind(this));
};

/**
 * Save address to db through Ajax call and validate state against the list of
 * countries if applicable.
 * @param event jQuery event object
 */
Address.prototype.confirmEditAddress = function(event) {

	var stateCode, countryId;
	if(event) {
		event.preventDefault();
	}

	stateCode = this.$recipientStateCode.val();
	countryId = this.$recipientCountry.val();


	// If the selected country has states, validate the selected state then save
	// the address. Otherwise, save the address directly
	if (stateCode.toLowerCase() !== 'n/a') {
		$.get(this.options.GET_STATES_BY_CODE_URL +
		'?stateCode=' + stateCode + '&countryId=' + countryId, function(data){

			if (data.status === 'error') {
				this.helper.showErrors('.address-new', data.errors);
				return;
			}

			this.helper.clearErrors();

			if (data.state != null && data.state.id != null) {
				this.$recipientStateId.val(data.state.id);
			}
				this.saveAddress();

		}.bind(this));
	}
	else {
		this.saveAddress();
	}
};

/**
 * Cancel Add or edit address and hide the form.
 * @param event
 */
Address.prototype.cancelAddress = function(event) {
	if(event) {
		event.preventDefault();
	}
	this.$addressList.removeClass('flipped');
	this.$addressForm.removeClass('flipped');
	this.$addressListTitle.show();
};

/**
 * Save Address
 */
Address.prototype.saveAddress = function () {

	var formData = this.helper.convertFormToObject('CustomerAddress', this.$accountAddressForm);

	// For some reason the backend expects residential address checkbox as "0" or "1" instead of true or false.
	formData.CustomerAddress.residential = formData.CustomerAddress.residential ? '1' : '0';

	$.ajax({
		type: 'POST',
		url: this.options.MY_ACCOUNT_ADDRESS_URL,
		dataType: 'json',
		data: formData,
		success: this.handleSaveAddressResponse.bind(this)
	});
};

/**
 * Handles Save address response, errors and refresh window.
 * @param data
 */
Address.prototype.handleSaveAddressResponse = function(data){
	if (data.status !== 'success') {
		this.helper.showErrors('.address-new', data.errors);
		return;
	}

	window.location.reload(true);
};

/**
 * Removes address from the address list display and clear any errors.
 * @param event jQuery event object
 */
Address.prototype.removeAddress = function (event) {
	var $target;
	if(event) {
		$target = $(event.target);
	}

	var selectedAddressId = $target.attr('data-address-id');

	this.helper.clearErrors();

	$.ajax({
		type: 'POST',
		url: this.options.MY_ACCOUNT_REMOVE_ADDRESS_URL,
		dataType: 'json',
		data: [{
			name: 'CustomerAddressId',
			value: selectedAddressId
		}, {
			name: 'YII_CSRF_TOKEN',
			value: this.$yiiCsrfToken.val()
		}],
		success: this.handleRemoveAddressResponse.bind(this, $target)
	});
};

/**
 * Handles the Remove address Response: deletes the address block.
 * @param event jQuery event object
 */
Address.prototype.handleRemoveAddressResponse = function($target){
	var addressBlock;
	if($target) {
		addressBlock = $target.parents('.address-block')[0];
		$(addressBlock).remove();
	}
};

/**
 * Country selection changed - handler.
 */
Address.prototype.recipientCountryChanged = function() {
	this.populateState(this.$recipientCountry.find('option:selected').val());
};

/**
 * Toggles the company input field.
 * @param event jQuery event object
 */
Address.prototype.toggleCompanyInput = function(event) {
	if(event) {
		event.preventDefault();
	}

	this.$fieldContainerNarrowed.removeClass('field-container-narrowed');
	this.$companyContainer.fadeIn();
	this.$companyContainer.find('input').focus();
	this.$fieldContainerToggle.remove();
};

