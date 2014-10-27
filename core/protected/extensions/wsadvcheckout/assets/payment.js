$('input[name="MultiCheckoutForm[billingPostal]"]').on('keyup', function(e) {

    var country = $("#MultiCheckoutForm_billingCountry :selected").attr('code');

    if(this.value.length > 2) {
        zippothatnow(this.value, country);
    }

    if(this.value.length < 3) {
        eraseCity();
        $('#MultiCheckoutForm_billingState').val('');
    }

});

$('#MultiCheckoutForm_billingCountry').change(function() {
    var postal = $('#MultiCheckoutForm_billingPostal').val();
    var country = $("#MultiCheckoutForm_billingCountry :selected").attr('code');

    if (postal.length > 2) {
        zippothatnow(postal, country);
    }
});

$('#MultiCheckoutForm_billingCity').change(function() {
    if ($('#MultiCheckoutForm_billingCity :selected').attr('value') == 'none'){
        eraseCity();
    }
});

function eraseCity() {
    var textInput = $('<input size="14" placeholder="City" required="required" name="MultiCheckoutForm[billingCity]" id="MultiCheckoutForm_billingCity" type="text" />');
    $('#ChooseCity').html(textInput);
    $('#MultiCheckoutForm_billingCity').val('');
}

function zippothatnow(postal, country) {

    // we can add to the switch list as necessary
    switch (country) {

        // for Great Britain and Canada, Zippopotam only uses the first 3 characters in the
        // query URL. In case someone pastes in the full postal, we account for it here
        case 'GB':
        case 'CA': postal = postal.substring(0,3); break
    }

    $.ajax({
        url: '//api.zippopotam.us/'+ country + '/' + postal,
        cache: false,
        dataType: 'json',
        success: function (data, success) {
            if (data['places'].length > 1) {
                var newSelect = $('<select id="MultiCheckoutForm_billingCity" name="MultiCheckoutForm[billingCity]"/>');
                for (var val in data['places']){
                    $('<option />', {value: data['places'][val]['place name'], text: data['places'][val]['place name']}).appendTo(newSelect);
                }
//                $('<option value="none">none of these</option>').appendTo(newSelect);
                $('#ChooseCity').html(newSelect);
                $('#MultiCheckoutForm_billingState').val(data['places'][0]['state abbreviation']);
            }
            else {
                eraseCity();
                places = data['places'][0];
                $('#MultiCheckoutForm_billingCity').val(places['place name']);
                $('#MultiCheckoutForm_billingState').val(places['state abbreviation']);
            }

        }

    });

}


