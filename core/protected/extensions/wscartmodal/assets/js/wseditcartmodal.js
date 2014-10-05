// Create a global for our methods.
var wseditcartmodal = {};

wseditcartmodal.redrawCart = function(shoppingCart) {

    var rowBaseId = 'cart_row_';

    if (shoppingCart.cartItems === undefined) {
        // Something went wrong, we don't have a cartItems property.
        return;
    }


    for (var itemIdx=0, numItems = shoppingCart.cartItems.length; itemIdx < numItems; ++itemIdx) {
        var cartItem = shoppingCart.cartItems[itemIdx];

        row = $('#' + rowBaseId + cartItem.id);
        if (row.length === 0) {
            // Something went wrong, the row being updated was not found in the HTML.
            continue;
        }

        var unitHTML = '';

        if(cartItem.discount !== '0'){
            unitHTML = '<strike>' + cartItem.sellFormatted + '</strike>';
            unitHTML += cartItem.sellDiscountFormatted;
        }
        else{
            unitHTML = cartItem.sellFormatted;
        }


        $(row).find('.price').html(unitHTML);

        $(row).find('.subtotal').html(cartItem.sellTotalFormatted);

        var id = '#CartItem_qty_'+ cartItem.id;
        $(id).val(cartItem.qty);
    }

    // Loop through the table if an item's qty = 0 the item no longer
    // exists in the shoppingCart JSON hence remove the corresponding row.
    $("#user-grid table tbody tr").each(function(index, element) {
        var rowId = $(element).attr("id");
        var found = false;
        for (var i = 0; i < shoppingCart.cartItems.length; i++) {
            if (rowBaseId + shoppingCart.cartItems[i].id === rowId) {
                found = true;
                break;
            }
        }
        if (found === false) {
            $("#" + rowId).addClass('delete');
                setTimeout(function() { $("#" + rowId).remove();},500);
        }
    });

    $('#CartSubtotal').html(shoppingCart.subtotalFormatted);
    $('#CartTotal').html(shoppingCart.totalFormatted);

    // if any kind of discount is applied in the cart, return its total in dollars in the total section.
    if (cartItem && cartItem.discounted === true) {
        $('#PromoCodeLine').removeClass('hide-me');
        $('#PromoCodeStr').html(shoppingCart.totalDiscountFormatted);
    }

    //remove the promo and discount line when the cart is empty
    if (shoppingCart.cartItems.length === 0){
        $(".editcartmodal.show .webstore-promo-line").remove();
    }

//    // if valid promo code was applied display its name in the total section
//    if (typeof shoppingCart.promoCode === 'string' && shoppingCart.promoCode !== "" ) {
//        $('#PromoCodeLine').removeClass('hide-me');
//        $('#PromoCodeStr').html(shoppingCart.promoCode);
//    }

    //update cart in top-bar navigation
    $('#cartItemsTotal').text(shoppingCart.totalItemCount);
};

wseditcartmodal.ajaxTogglePromoCode = function(cartId) {
    if($('.promocode-apply').hasClass('promocode-applied')){
        wseditcartmodal.ajaxRemovePromoCode(cartId);
    } else {
        wseditcartmodal.ajaxApplyPromoCode(cartId);
    }
}

wseditcartmodal.ajaxApplyPromoCode = function (cartId) {
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
                // TODO: Recalculate shipping and taxes.
            } else if (data.action === 'success') {
                $(".editcartmodal.show .webstore-promo-line").show();
                var $this = $('.promocode-apply');
                $this.addClass('promocode-applied');
                $this.html("Remove"); // TODO: translation
                wseditcartmodal.redrawCart(JSON.parse(data.shoppingCart));
                $('#' + cartId).prop('readonly', true);
            }
        }
    });
};

wseditcartmodal.ajaxRemovePromoCode = function (cartId) {
    jQuery.ajax({
        type: 'POST',
        url: '/cart/modalremovepromocode',
        dataType: 'json',
        success: function(data) {
            var $this = $('.promocode-apply');
            $this.removeClass('promocode-applied');
            $(".editcartmodal.show .webstore-promo-line").hide();
            $this.html("Apply"); // TODO: translation
            $("#EditCart_promoCode").val("");
            wseditcartmodal.redrawCart(JSON.parse(data.shoppingCart));
            $('#' + cartId).prop('readonly', false);
        }
    });
};

wseditcartmodal.ajaxTogglePromoCodeEnterKey = function(event, cartId){
    if (event.keyCode === 13){
        wseditcartmodal.ajaxTogglePromoCode(cartId);
        event.preventDefault();
    }
}

wseditcartmodal.ajaxClearCart = function () {
    jQuery.ajax({
        data: null,
        type: 'POST',
        url: '/cart/clearcart',
        dataType: 'json',
        success: function(data){
            if (data.action=="alert") {
                alert(data.errormsg);
            } else if (data.action=="success") {
                return;
            }}
        });
};

wseditcartmodal.tooltip = {};

wseditcartmodal.tooltip.createTooltip = function(targetId, message) {
    wseditcartmodal.tooltip.targetId = targetId;
    wseditcartmodal.tooltip.creatingTooltip = true;
    var target = $('#' + targetId);
    var targetOffset = target.offset();
    $('body').append('<div class=\'alert-tooltip\'>' + message + '</div>');
    var tooltip = $('.alert-tooltip');
    tooltip.offset({top: targetOffset.top - tooltip.height() / 2 - 50, left: targetOffset.left - tooltip.width() / 2});
    setTimeout(function() {
        $(".alert-tooltip").fadeOut(500, function() {$(this).remove();});
    }, 4000)
}

wseditcartmodal.tooltip.adjustPosition = function() {
    var tooltip = $(".alert-tooltip");
    tooltip.remove();
    var targetId = wseditcartmodal.tooltip.targetId;
    var target = $('#' + targetId);
    var targetOffset = target.offset();
    if (targetOffset != null)
    tooltip.offset({top: targetOffset.top - tooltip.height() / 2 - 50, left: targetOffset.left - tooltip.width() / 2});

}

//$(window).on('resize', function() {
//    wseditcartmodal.tooltip.adjustPosition();
//});

//window.onscroll = function() {
//    wseditcartmodal.tooltip.adjustPosition();
//};

$(document).on('click', function() {
    if (wseditcartmodal.tooltip.creatingTooltip === false){

        $(".alert-tooltip").fadeOut(500, function(){$(this).remove();});
    }
    wseditcartmodal.tooltip.creatingTooltip = false;
})

$(document).on('click', "#cart .exit, #cart .continue",function()  {
    hideModal();
});

