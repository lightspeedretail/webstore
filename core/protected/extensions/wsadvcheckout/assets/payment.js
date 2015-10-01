/* globals $ */
$(function() {
    'use strict';

    // on browser refresh or in the event of an error, display the
    // subform input fields for the selected payment provider
    var el = $('input[name="MultiCheckoutForm[paymentProvider]"]:checked').val();
    $('.subform.payform_' + el + ' input').show();

    // only display the input fields for the selected radio option
    $('input[name="MultiCheckoutForm[paymentProvider]"]').on('change', function() {
        $('.subform input').fadeOut();
        var classProvider = 'payform_' + $('input[name="MultiCheckoutForm[paymentProvider]"]:checked').val();
        $('.subform.' + classProvider +' input').fadeIn();
    });

    $('input[name="MultiCheckoutForm[billingPostal]"]').on('keyup', function () {
        var country = $('#MultiCheckoutForm_billingCountryCode :selected').val();

        if (this.value.length > 2) {
            var zippoOptions = {'section': 'billing', 'postal': this.value, 'country': country};
            zippothatnow(zippoOptions);
        }

        if (this.value.length < 3) {
            eraseCity('billing');
            $('#MultiCheckoutForm_billingStateCode').val('');
        }

    });

    $('#payment').on('submit', function() {
        // WS-3056 - Perform zippo lookup for state on submit
        // of shipping address form and billing address form
        var zippoOptions = {'section': 'billing', 'async': false, 'eraseCity': false};
        zippoLookup(zippoOptions);
    });

    $('#MultiCheckoutForm_billingCity').change(function() {
        if ($('#MultiCheckoutForm_billingCity :selected').attr('value') === 'none') {
            eraseCity('billing');
        }
    });
});



