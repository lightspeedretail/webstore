'use strict';
/* globals $: false, helper: false */

/**
 * @class Password
 * @param options
 * @constructor
 */
function Password (options) {
	this.options = options || {};
	this.helper = helper;
	this.$body = $('body');
	this.$modalPassword = $('#modal-password');
	this.$changePasswordForm = $('#change-password-form');
	this.$passwordField = $('[name=password]');
	this.$changePasswordLink = $('#change-password-link');
	this.$changePasswordSubmit = $('#change-password-submit');
	this.$cancel = this.$modalPassword.find('.cancel');
	this.bindEvents();
}

/**
 * Bind events
 */
Password.prototype.bindEvents = function() {
	this.$changePasswordLink.on('click', this.showChangePassword.bind(this));
	this.$changePasswordSubmit.on('click', this.changePassword.bind(this));
	this.$cancel.on('click', this.cancel.bind(this));
	$(document).on('hideModal', this.hideModalHandler.bind(this));
};

/**
 * Shows the Change Password modal and removes scrollbar.
 * @param event jQuery event object
 */
Password.prototype.showChangePassword = function (event) {
	if(event) {
		event.preventDefault();
	}
	this.helper.clearErrors();
	this.$modalPassword.addClass('show');
	// Remove the body's scrollbar when opening a modal
	this.$body.css('overflow', 'hidden');
};

/**
 * Post new password data and form validation (the password cannot be blank).
 * @param event jQuery event object
 */
Password.prototype.changePassword = function (event) {
	if(event) {
		event.preventDefault();
	}

	this.helper.clearErrors();

	// Validation for empty password fields.
	// We cannot do validation on the backend because the old account theme
	// permits empty password fields.
	if ($.trim(this.$passwordField.val()) === '') {
		return this.helper.showErrors(
			'#change-password-form',
			this.options.PASSWORD_CANNOT_BE_BLANK
		);
	}

	var formData = this.helper.convertFormToObject(
		'Customer',
		this.$changePasswordForm
	);

	$.ajax({
		type: 'POST',
		url: this.options.UPDATE_PASSWORD_URL,
		dataType: 'json',
		data: formData,
		success: this.handleChangePasswordResponse.bind(this)
	});
};

/**
 * Handles the changePassword response and clear form and errors.
 * @param data customer data object
 */
Password.prototype.handleChangePasswordResponse = function(data){
	this.helper.clearErrors();
	if (data.status !== 'success') {
		this.helper.showErrors('#change-password-form', data.errors);
		return;
	}
	this.$modalPassword.removeClass('show');
	this.$changePasswordForm.find('input[type=password]').val('');
	// Restore the body's scrollbar when hiding a modal
	this.$body.css('overflow', 'inherit');
};

/**
 * Cancel modal.
 */
Password.prototype.cancel = function() {
	window.hideModal();
};

/**
 * When a modal is hidden, clear the fields.
 */
Password.prototype.hideModalHandler = function() {
	this.$changePasswordForm.find('input[type=password]').val('');
};