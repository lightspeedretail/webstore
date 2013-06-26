<?php
/* The javascript variable  below is populated from the Ajax call to get shipping prices. We store it client-side
to make clicking methods faster. The returned shipping options look for js:updateShippingPriority() so this
must exist

Since our AJAX replaces the controls, we have to set ytCheckoutForm_shippingProvider and
ytCheckoutForm_shippingPriority manually so they're included
in our submitted form.

This is a PHP file and not a .js file since we have to call our model functions as part of render
If we have gone to checkout previously, we can load our cached shipping information instead of running recalculate every time
*/
?><script>
    var savedShippingProviders = '<?= $model->getSavedProvidersRadio() ?>';
    var savedShippingPriorities = <?= $model->getSavedPrioritiesRadio() ?>;
    var savedTaxes = <?= $model->getSavedTax() ?>;
    var savedShippingPrices = <?= $model->getSavedPrices() ?>;
    var savedTotalScenarios = <?= $model->getSavedScenarios() ?>;
    var savedCartScenarios = <?= $model->getSavedCartScenarios() ?>;
    var pickedShippingProvider = '<?= $model->shippingProvider; ?>';
    var pickedShippingPriority = '<?= $model->shippingPriority; ?>';
    function updateShippingPriority(id)	{
        $("#<?=CHtml::activeId( $model, 'shippingPriority')?>" ).html(savedShippingPriorities[id]);
        $("#ytCheckoutForm_shippingProvider").val(id);
        $("#ytCheckoutForm_shippingPriority").val(null);
        pickedShippingProvider = id;
        pickedShippingPriority = 0;
        $("#shippingPriority_0").click();
    }
    function updateCart(id)	{
        if(id) {
	        $("#cartItems").html(savedCartScenarios[pickedShippingProvider]);
	        $("#cartShipping").html(savedShippingPrices[pickedShippingProvider][id]);
	        $("#cartTaxes").html(savedTaxes[pickedShippingProvider][id]);
	        $("#cartTotal").html(savedTotalScenarios[pickedShippingProvider][id]);
            pickedShippingPriority = id;
            $("#ytCheckoutForm_shippingPriority").val(id);
        }
    }
    function updateShippingAuto() {
        if(    ( $("#<?= CHtml::activeId($model,'intShippingAddress') ?>").val() ) ||
	        (   $("#<?= CHtml::activeId($model,'shippingAddress1') ?>").val() &&
                $("#<?= CHtml::activeId($model,'shippingAddress2') ?>").val() &&
                $("#<?= CHtml::activeId($model,'shippingCity') ?>").val() &&
                $("#<?= CHtml::activeId($model,'shippingState') ?>").val() &&
                $("#<?= CHtml::activeId($model,'shippingPostal') ?>").val()
                ))
            $("#btnCalculate").click();
    }
    function updateTax(data) {
        $("#cartItems").html(data.cartitems);
        if (data.action=="triggerCalc")
            $("#btnCalculate").click();
    }
    function changePayment(data) {
        var cc = new Array(<?php echo $model->getPaymentModulesThatUseCard() ?>);
        var op = new Array(<?php echo $model->getPaymentModulesThatUseForms(true) ?>);
        if($.inArray(data,cc)> -1) $("#CreditCardForm").show();
        else $("#CreditCardForm").hide();
        $.each(op, function (index,value) {$("#payform"+value).hide()});
        if($.inArray(data,op)> -1) $("#payform"+data).show();
    }
   if (pickedShippingPriority>-1)
	   $("#<?=CHtml::activeId( $model, 'shippingPriority')?>_"+pickedShippingPriority).click();
</script>