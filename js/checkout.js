
function processPromo(data) {
    if (data.action=="alert") alert(data.errormsg);
    else if (data.action=="error") {
        $("#CheckoutForm_promoCode_em_").html(data.errormsg);
        $("#CheckoutForm_promoCode_em_").show();
    } else if (data.action=="success") {
        $("#CheckoutForm_promoCode_em_").html(data.errormsg);
        $("#CheckoutForm_promoCode_em_").show();
        $("#cartItems").html(data.cartitems);
        if (savedShippingProviders)
            $("#btnCalculate").click();
    }
}

