/* globals $ */
'use strict';

$(function() {

    // Should be done with a selector control such as...
    $('.instore-toggle').on('change', function () {
        $('.modal-conditional-block').toggleClass('active');

        setTimeout(function () {
            $('.modal-conditional-block.active').find('input[autofocus]').first().focus();
        }, 500);

    });

    $('input[name="MultiCheckoutForm[shippingPostal]"]').focusout(function () {
        var country = $('#MultiCheckoutForm_shippingCountryCode :selected').val();

        if (this.value.length > 2) {
            var zippoOptions = {'section': 'shipping', 'postal': this.value, 'country': country};
            zippothatnow(zippoOptions);
        }

        if (this.value.length < 3) {
            eraseCity('shipping');
            $('#MultiCheckoutForm_shippingStateCode').val('');
        }

    });

    $('#shipping').on('submit', function() {
        //WS-3056 - Perform zippo lookup for state on submit of shipping address
        // form and billing address form
        var zippoOptions = {'section': 'shipping', 'async': false, 'eraseCity': false};
        zippoLookup(zippoOptions);
    });

    $('#MultiCheckoutForm_shippingCity').change(function () {
        if ($('#MultiCheckoutForm_shippingCity :selected').attr('value') === 'none') {
            eraseCity('shipping');
        }
    });

    var $shippingOptions = $('input[name=shippingOption]:checked');
    if ($shippingOptions.length === 0) {
        $($('input[name=shippingOption]')[0]).next().click();
        $($('input[name=shippingOption]')[0]).prop('checked', true);
    }
});
