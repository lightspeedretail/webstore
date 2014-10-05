// Should be done with a selector control such as...
$('.instore-toggle').on('change', function(e) {
    $('.modal-conditional-block').toggleClass('active');

    setTimeout(function() { $('.modal-conditional-block.active').find('input[autofocus]').first().focus(); }, 500);

});

$('input[name="MultiCheckoutForm[shippingPostal]"]').on('keyup', function(e) {

    var country = $("#MultiCheckoutForm_shippingCountry :selected").val();

    if(this.value.length > 2) {
        zippothatnow(this.value, country);
    }

    if(this.value.length < 3) {
        eraseCity();
        $('#MultiCheckoutForm_shippingState').val('');
    }

});

$(".delete").on('click', function(e)    {
   var cached_link = $(this);
   var customer_address_id = $(this).data('customerAddressId');
   var request = $.ajax({
        type: "POST",
        url: "/myaccount/removeaddress",
        data: { CustomerAddressId: customer_address_id }
    });

   request.done(function(data)  {
      if(data.status === "success") {
          var address_block = $(cached_link).parents(".address-block")[0];
          $(address_block).remove();
      }
   });

   return false;
});

$('#MultiCheckoutForm_shippingCountry').change(function() {
    var postal = $('#MultiCheckoutForm_shippingPostal').val();
    var country = $("#MultiCheckoutForm_shippingCountry :selected").val();

    if (postal.length > 2) {
        zippothatnow(postal, country);
    }
});

$('#MultiCheckoutForm_shippingCity').change(function() {
    if ($('#MultiCheckoutForm_shippingCity :selected').attr('value') == 'none'){
        eraseCity();
    }
});

function eraseCity() {
    var textInput = $('<input size="14" placeholder="City" required="required" name="MultiCheckoutForm[shippingCity]" id="MultiCheckoutForm_shippingCity" type="text" />');
    $('#ChooseCity').html(textInput);
    $('#MultiCheckoutForm_shippingCity').val('');
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
        url: 'http://api.zippopotam.us/'+ country + '/' + postal,
        cache: false,
        dataType: 'json',
        success: function (data, success) {
            if (data['places'].length > 1) {
                var newSelect = $('<select id="MultiCheckoutForm_shippingCity" name="MultiCheckoutForm[shippingCity]"/>');
                for (var val in data['places']){
                    $('<option />', {value: data['places'][val]['place name'], text: data['places'][val]['place name']}).appendTo(newSelect);
                }
//                $('<option value="none">none of these</option>').appendTo(newSelect);
                $('#ChooseCity').html(newSelect);
                $('#MultiCheckoutForm_shippingState').val(data['places'][0]['state abbreviation']);
            }
            else {
                eraseCity();
                places = data['places'][0];
                $('#MultiCheckoutForm_shippingCity').val(places['place name']);
                $('#MultiCheckoutForm_shippingState').val(places['state abbreviation']);
            }

        }

    });

}
