'use strict';
/* globals $, cityPlaceholder: false */

/**
 * Erase the end-user defined city from the form
 *
 * @param section - 'billing' or 'shipping'
 */
function eraseCity(section)
{
    var textInput =
        $('<input size="14" placeholder="'+ cityPlaceholder +
            '" required="required" name="MultiCheckoutForm['+ section + 'City]" id="MultiCheckoutForm_'+ section +'City" type="text" />'
        );
    $('#ChooseCity').html(textInput);
    $('#MultiCheckoutForm_'+ section +'City').val('');
}

/**
 * For Great Britain and Canada, Zippopotam.us only uses the first 3 characters in the
 * query URL. In case someone pastes in the full postal, we account for it here. We
 * can add to the switch list as necessary.
 *
 * @param country - 2 character ISO country code
 * @param postal - end-user defined zip/postal code
 * @returns {*}
 */
function parsePostal(country, postal)
{
    switch (country) {
        case 'GB':
        case 'CA':
            return postal.substring(0, 3);

        default:
            return postal;
    }
}

/**
 * Make the API call to Zippo, parse the response for the State Abbreviation
 * and automatically place it in the relevant input field.
 *
 * @param options
 *      options.section => 'billing' or 'shipping'
 *      options.country => 2 character ISO country code
 *      options.postal => end-user defined zip/postal code
 *      options.async => boolean (only included when function is called from zippoLookup())
 *      options.eraseCity => boolean (only included when function is called from zippoLookup())
 */
function zippothatnow(options)
{
    options.postal = parsePostal(options.country, options.postal);

    $.ajax({
        url: 'https://api.zippopotam.us/' + options.country + '/' + options.postal,
        cache: false,
        dataType: 'json',
        async: options.async || false
    }).success(function (data) {
        if (options.eraseCity === true) {
            eraseCity(options.section);
        }

        if (data.places) {
            $('#MultiCheckoutForm_'+ options.section +'StateCode').val(getProvinceAbbreviation(data.places[0]['state abbreviation']));
        }
    });
}

/**
 * We make another call to Zippo on submit of the Shipping/Payment pages
 * via this function. This ensures the integrity of the state code despite
 * an inadvertent or deliberate change to it after it has been set.
 *
 * @param options
 *      options.section => 'billing' or 'shipping'
 *      options.async => boolean
 *      options.eraseCity => boolean
 */
function zippoLookup(options)
{
    var country = $('#MultiCheckoutForm_'+ options.section +'CountryCode :selected').val();
    var postal = $('#MultiCheckoutForm_'+ options.section +'Postal').val();

    // TODO WS-4406 Refactor Country dropdown on payment page to use CountryCode
    // We can remove this check once WS-4406 is complete
    if (options.section === 'billing')
    {
        country = $('#MultiCheckoutForm_'+ options.section +'Country :selected').attr('code');
    }

    if (postal.length > 2) {
        options.country =  country;
        options.postal = postal;
        zippothatnow(options);
    }
}

/**
 * WS-4396 - Zippopotamus sometimes returns province name instead of province abbreviation
 *
 * This function has become necessary due to Zippo's response shenanigans for the following endpoints,
 * https://api.zippopotam.us/CA/E2E
 * https://api.zippopotam.us/CA/K0C
 *
 * GitHub issue - https://github.com/ekotechnology/Zippopotamus-Cloud/issues/21
 *
 * We will update the function as necessary if more are discovered.
 *
 * @param state
 * @returns {*}
 */
function getProvinceAbbreviation(state)
{
    switch (state.toLowerCase()) {
        case 'ontario':
            return 'ON';

        case 'new brunswick':
            return  'NB';

        default:
            return state;
    }
}
