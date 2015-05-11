$(function () {
    "use strict";
    /* globals $, login:false, checkout:false */

    login.showClass = "show";

    // Clear errors
    login.clearErrors = function(form) {
        form
            .removeClass('error').end()
            .find('.required').remove().end()
            .find('.form-error').remove().end();

        form.removeClass('success').end();
    };

    // Render error
    login.renderError = function(form, error_message) {
        // Clear errors
        login.clearErrors(form);

        var error_dom = $('<div class="form-error">').append($('<p>').append(error_message));
        error_dom.hide();

        if (form.find('.error-holder').length > 0) {
            form.addClass('error').find('.error-holder').prepend(error_dom);
        } else {
            form.addClass('error').prepend(error_dom);
        }

        form.find('input,select').each(function (i, field) {
            if (!field.validity.valid && $(field).parents('.field-container').length > 0) {
                $(field).parent().prepend($('<span class="required">Required</span>'));
            }
        });

        error_dom.fadeIn();

        // Shake like crazy, then let us do it again!
        $('.webstore-modal-overlay > section').addClass('animated shake');
        setTimeout(function () {
            $('.webstore-modal-overlay > section').removeClass('animated shake');
        }, 500);
    };

    // Render success
    login.renderSuccess = function(form, success_message) {
        // Clear errors
        login.clearErrors(form);

        var success_dom = $('<div class="form-error">').append($('<p>').append(success_message));
        success_dom.hide();

        if (form.find('.error-holder').length > 0) {
            form.addClass('success').find('.error-holder').prepend(success_dom);
        } else {
            form.addClass('success').prepend(success_dom);
        }
        success_dom.fadeIn();
    };

    // Show password panel
    login.showPasswordPanel = function() {
        var password_block = $(".password-block");
        password_block.toggleClass("flip");
        setTimeout(function () {
            $("#LoginForm_password").focus();
        }, 200);
        login.changeFooter('login');
    };

    // Is login form
    login.isLoginForm = function()  {
        return $('footer .login').hasClass('invisible') === false &&
            $('footer .login input').hasClass('is-login');
    };

    // Validate form
    login.validateForm = function(e) {
        var form = $('form');
        var loginButton = $("#login-button");
        login.clearErrors(form);

        // Check if we're in the login user form
        if (login.isLoginForm()) {
            $('#IsGuest').prop('checked', false);
        }

        // Always validate email for submission
        if (!$('input[type="email"]')[0].validity.valid) {
            login.renderError(form, advcheckoutTranslation.INVALID_EMAIL);
            $('input[type="email"]').focus();
            return false;

        // If login form is visible and not in "reset password" mode validate password field
        } else if (
            $('footer .login').hasClass('invisible') === false &&
            loginButton.hasClass("is-login") &&
            ($('input[type="password"]')[0].validity.valid === false || $($('input[type="password"]')[0]).val() === "")
        ) {
            login.renderError(form, advcheckoutTranslation.PASSWORD_REQUIRED);
            $("#LoginForm_password").focus();
            return false;
        }

        return true;
    };

    // Change footer
    login.changeFooter = function(footerClass) {
        $('footer > div').addClass('invisible');
        $('footer div.' + footerClass).removeClass('invisible');
    };

    // Form title text
    login.formTitleText = function(new_title) {
        var form_title = $('form h1');

        if (form_title.attr('data-original') !== undefined) {
            form_title.attr('data-original', form_title.text());
        }

        form_title.text(new_title);
    };

    // On load
    login.onLoad = function() {
        var checkoutLink = $("a.guest_checkout");

        // If guest decided to come back and login, show the login panel
        if (login.blnShowLogin === "true") {
            $('#IsGuest').prop('checked', true);
            $('#IsGuest').prop('value', 1);
            login.showPasswordPanel();
            checkoutLink.text('Return to Checkout');
        }

        // If there were errors such as invalid password, keep the password form after refresh
        else if ($(".form-error").length > 0 &&
                login.showLoginPasswordField) {
            login.showPasswordPanel();
        }

        // if guest already entered their email at some point, fill it out
        if (login.contactEmail) {
            $("#LoginForm_email").val(login.contactEmail);
        }
    };



    // Run startup function
    login.onLoad();



    // User Event Handlers //

    // If they press enter, validate email
    $("#LoginForm_email").on('keydown', function(e) {
        var code = e.which,
            val = $(this).val();

        if (code === 13) {
            if (val === "") {
                login.renderError($('form'), advcheckoutTranslation.EMAIL_REQUIRED);
                $(this).focus();
                return false;
            }
            else if ($('input[type="email"]')[0].validity.valid === false) {
                login.renderError($('form'), advcheckoutTranslation.INVALID_EMAIL);
                $(this).focus();
                return false;
            }
        }
    });

    // Login/Forgot Password button click
    $("#login-button").on('click', function (e) {
        if (login.validateForm(e) === false) {
            return false;
        }

        var loginButton = $("#login-button");

        // if forgot password, send ajax request. Else if login, submit form normally (no js required - it's a regular submit in this case)
        if (loginButton.hasClass("is-login") === false) {
            $.ajax({
                'type':'POST',
                'data': $("form").serialize(),
                'dataType': 'json',
                'url': login.forgotPasswordLink,
                'cache': false,
                'success': function(data) {
                    if (data.status === "success") {
                        login.renderSuccess($("form"), data.message);
                        var loginButton = $("#login-button");
                        var old_submit_name = loginButton.attr("value");
                        var password_block = $(".password-block");

                        loginButton
                            .attr("value", loginButton.attr("data-alt-name"))
                            .attr("data-alt-name", old_submit_name);
                        loginButton.addClass("is-login");

                        password_block.find(".step1").removeClass("front");
                        password_block.find(".step2, .step3").toggleClass("front");
                        password_block.toggleClass("flip");
                        $.ajax({url:data.url});
                    } else {
                        login.renderError($("form"), data.message);
                    }
                }
            });

            return false;
        }
    });


    // I've Ordered Before button click
    $('.ordered_before').on('click', function (e) {
        login.clearErrors($('form'));
        e.preventDefault();

        // Activate 'Existing User' validation
        $('#IsGuest').prop('checked', false);
        $('#IsGuest').prop('value', 0);

        // Flip out "why? ..." statement for password field
        $('.password-block').toggleClass('flip');

        // Change to login button
        login.changeFooter('login');

        // Change title
        login.formTitleText(advcheckoutTranslation.LOGIN_TITLE);

        setTimeout(function () {
            $('input[type="password"]').focus();
        }, 200);
    });

    // I'm a new customer button click
    $('input.new_customer').on('click', function (e) {
        var form = $('form');

    // Activate 'Guest' validation
        $('#IsGuest').prop('checked', true);
        $('#IsGuest').prop('value', 1);

        if ($('#LoginForm_email').val() === "") {
            $('#LoginForm_email').focus();
            checkout.renderError(form, advcheckoutTranslation.EMAIL_REQUIRED);
            return false;
        }
        else if ($('#LoginForm_email')[0].validity.valid === false) {
            checkout.renderError(form, advcheckoutTranslation.INVALID_EMAIL);
            $('#LoginForm_email').focus();
            return false;
        }
    });

    // Guest Checkout button
    $('.guest input').on('click', function (e) {
        var form = $('form');

        // Activate 'Guest' validation
        $('#IsGuest').prop('checked', true);
        $('#IsGuest').prop('value', 1);

        if ($('#LoginForm_email').val() === "") {
            $('#LoginForm_email').focus();
            login.renderError(form, advcheckoutTranslation.EMAIL_REQUIRED);
            return false;
        }
        else if ($('#LoginForm_email')[0].validity.valid === false) {
            login.renderError(form, advcheckoutTranslation.INVALID_EMAIL);
            $('#LoginForm_email').focus();
            return false;
        }
    });


    // Checkout as Guest link click
    $('.guest_checkout').on('click', function (e) {

        var form = $('form');

        // only click cancel if we're on the "forgot password" screen
        var $cancel= $('.cancel');
        if ($("#login-button").hasClass("is-login") === false && $cancel.length > 0) {
            $cancel.trigger("click");
        }
        // Activate 'Guest' validation
        $('#IsGuest').prop('checked', true);
        $('#IsGuest').prop('value', 1);

        // If guest is coming back from shipping page to login but then decided to go back to the shipping page
        //TODO: return to the page they are coming from. Right now it will just submit which will take you to shipping
        if (login.blnShowLogin === "true") {
            form.submit();
            return false;
        }

        login.formTitleText(advcheckoutTranslation.GUEST_CHECKOUT_TITLE);

        // Change to 'Guest Checkout' button
        login.changeFooter('guest');

        // Flip to password reset
        var password_block = $('.password-block');
        password_block.find('.step1').addClass('front');
        password_block.find('.step2, .step3').removeClass('front');
        password_block.removeClass('flip');

        return false;
    });


    // Reset toggle
    $('.reset_toggle').on('click', function (e) {
        e.stopPropagation();
        // Change button label
        var button = $('footer .login input');

        var old_submit_name = button.attr('value');
        button
            .attr('value', button.attr('data-alt-name'))
            .attr('data-alt-name', old_submit_name);

        button.toggleClass("is-login");

        // Flip to password reset
        var password_block = $('.password-block');
        password_block.find('.step1').removeClass('front');
        password_block.find('.step2, .step3').toggleClass('front');
        password_block.toggleClass('flip');
    });


    // Validation
    $('.webstore-overlay').find("input,select").bind("checkval", function () {
        var hints_labels = $(this).closest('.field-container').find(".hint, label");

        if (this.value !== "" || (!this.validity.valueMissing && !this.validity.valid)) {
            hints_labels.addClass(login.showClass);
            $(this).addClass(login.showClass);

            if (!this.validity.valid && !this.validity.valueMissing) {
                $(this).parents('form').addClass('error');
            }
        } else {
            hints_labels.removeClass(login.showClass);
            $(this).removeClass(login.showClass);
        }
    })
        .on("keyup", function () {
            $(this).trigger("checkval");
        })
        .trigger("checkval");


    //
    $("select").on("change", function () {
        var hints_labels = $(this).closest('.field-container').find(".hint, label");
        if (this.value !== "") {
            hints_labels.addClass(login.showClass);
            $(this).addClass(login.showClass);
        } else {
            hints_labels.removeClass(login.showClass);
            $(this).removeClass(login.showClass);
        }
    });
});
