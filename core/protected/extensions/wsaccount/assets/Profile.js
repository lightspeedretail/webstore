'use strict';
/* globals $: false, helper: false */

/**
 * @class Profile
 * @param options
 * @constructor
 */
function Profile (options) {
	this.options = options || {};
	this.helper = helper;
	this.currentCustomer = {};
	this.$body = $('body');
	this.$modalProfile = $('#modal-profile');
	this.$editProfileForm = $('#edit-profile-form');
	this.$firstName = $('#edit-first-name');
	this.$lastName = $('#edit-last-name');
	this.$email = $('#edit-email');
	this.$phone = $('#edit-phone');
	this.$newsletter = $('#edit-newsletter-subscribe');
	this.$labelFirstName = $('#label-first-name');
	this.$labelLastName = $('#label-last-name');
	this.$labelEmail = $('#label-email');
	this.$labelMainPhone = $('#label-main-phone');
	this.$editProfile = $('#edit-profile');
	this.$saveProfile = $('#save-profile');
	this.$cancel = this.$modalProfile.find('.cancel');
	this.bindEvents();
}

/**
 * Bind events
 */
Profile.prototype.bindEvents = function() {
	this.$editProfile.on('click', this.editProfile.bind(this));
	this.$saveProfile.on('click', this.saveProfile.bind(this));
	this.$cancel.on('click', this.cancel.bind(this));
};

/**
 * Show the Edit Profile modal and calls handleEditProfileResponse function to
 * populate it with the customer's profile data.
 * @param event
 */
Profile.prototype.editProfile = function(event) {
	if(event){
		event.preventDefault();
	}

	$.get(this.options.UPDATE_ACCOUNT_URL, this.handleEditProfileResponse.bind(this));
};

/**
 * Populates customer's profile form.
 * @param data profile data object
 */
Profile.prototype.handleEditProfileResponse = function(data) {
	if (data.status === 'error') {
		this.helper.showErrors('#edit-profile-form', data.errors);
		return;
	}

	this.currentCustomer = data.customer;

	this.$firstName.val(data.customer.first_name);
	this.$lastName.val(data.customer.last_name);
	this.$email.val(data.customer.email);
	this.$phone.val(data.customer.mainphone);
	this.$newsletter.prop('checked', data.customer.newsletter_subscribe === '1');
	this.helper.clearErrors();
	this.$modalProfile.addClass('show');

	// Remove the body's scrollbar when opening a modal
	this.$body.css('overflow', 'hidden');
};

/**
 * Save profile ajax post and clear errors.
 */
Profile.prototype.saveProfile = function(event) {
	if(event){
		event.preventDefault();
	}

	this.helper.clearErrors();

	var formData = this.helper.convertFormToObject('Customer', this.$editProfileForm, this.currentCustomer);

	formData.Customer.newsletter_subscribe = formData.Customer.newsletter_subscribe ? '1' : '0';

	$.ajax({
		type: 'POST',
		url: this.options.UPDATE_ACCOUNT_URL,
		dataType: 'json',
		data: formData,
		success:this.handleSaveProfileResponse.bind(this)
	});
};

/**
 * Handles Save profile response, populates profile display or show errors.
 * @param data saved profile data returned by the backend
 */
Profile.prototype.handleSaveProfileResponse = function(data){
	if (data.status !== 'success') {
		this.helper.showErrors('#edit-profile-form', data.errors);
		return;
	}

	this.$labelFirstName.text(data.customer.first_name);
	this.$labelLastName.text(data.customer.last_name);
	this.$labelMainPhone.text(data.customer.mainphone);
	this.$labelEmail.text(data.customer.email);
	this.$modalProfile.removeClass('show');

	// Restore the body's scrollbar when hiding a modal
	this.$body.css('overflow', 'inherit');
};

/**
 * Cancel modal.
 */
Profile.prototype.cancel = function() {
	window.hideModal();
};
