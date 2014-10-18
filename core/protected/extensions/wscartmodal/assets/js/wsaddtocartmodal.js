//TODO: code in confirmation.js, wseditcartmodal.js and wsaddtocartmodal.js has a lot of duplicates.
//TODO: it has to be refractored in the meantime please make sure to update all files.
// Create a global for our methods.
var wsaddtocartmodal = {};

wsaddtocartmodal.ajaxTogglePromoCode = function(cartId) {
    if($('.promocode-apply').hasClass('promocode-applied')){

        wsaddtocartmodal.ajaxRemovePromoCode(cartId);

    } else {

        wsaddtocartmodal.ajaxApplyPromoCode(cartId);

    }
}

wsaddtocartmodal.ajaxApplyPromoCode = function (cartId) {
    var promoCodeValue = $('#' + cartId).val();
    $('#' + cartId + '_em_').hide();

    jQuery.ajax({
        data: {
            promoCode: promoCodeValue
        },
        type: 'POST',
        url: '/cart/modalapplypromocode',
        dataType: 'json',
        success: function(data) {
            if (data.action === "alert") {
                alert(data.errormsg);
            } else if (data.action === "error") {
                $('#' + cartId + '_em_')
                    .find('p')
                    .text(data.errormsg)
                    .end()
                    .hide()
                    .fadeIn();
            } else if (data.action === "triggerCalc") {
                alert(data.errormsg);
                wsaddtocartmodal.closeThenOpenEditCart();
            } else if (data.action === 'success') {
                //$(".webstore-modal-cart-confirm .webstore-promo-line").show();
                var $this = $('.promocode-apply');
                $this.addClass('promocode-applied');
                $('.webstore-modal-cart-confirm .webstore-promo-line').removeClass('hide-me');
                $this.html("Remove"); // TODO: translation
                //why are we doing this? opening the edit cart modal when a promo code is applied in the add to cart is an unexpected behavior.
//                wsaddtocartmodal.closeThenOpenEditCart();
                var shoppingCart = JSON.parse(data.shoppingCart);
                $('#addtocart-promodiscount').html(shoppingCart.totalDiscountFormatted);
                $('#addtocart-subtotal').html(shoppingCart.subtotalFormatted);
                $('#' + cartId).prop('readonly', true);
            }
        }
    });
};

wsaddtocartmodal.ajaxRemovePromoCode = function (cartId) {
    jQuery.ajax({
        type: 'POST',
        url: '/cart/modalremovepromocode',
        dataType: 'json',
        success: function(data) {
            var $this = $('.promocode-apply');
            $this.removeClass('promocode-applied');
            $('.webstore-modal-cart-confirm .webstore-promo-line').addClass('hide-me');
            $this.html("Apply"); // TODO: translation
            $("#ShoppingCart_promoCode").val("");
            wseditcartmodal.redrawCart(JSON.parse(data.shoppingCart));
            $('#' + cartId).prop('readonly', false);
        }
    });
};

wsaddtocartmodal.ajaxTogglePromoCodeEnterKey = function(event, cartId){
    if (event.keyCode === 13){
        wsaddtocartmodal.ajaxTogglePromoCode(cartId);
        event.preventDefault();
    }
}

$(document).on('click', ".webstore-modal-close, .continue-shopping", function()  {
    hideModal();
});

$(document).on('click', ".webstore-change-item", function()  {
    hideModal();
    setTimeout(function(){
        showEditCartModal();
    }, 1125);
});
