<div class="row">
    <div class="cart_price col-sm-6"><h4><?= Yii::t('cart','Subtotal'); ?></h4></div>
    <div id="cartSubtotal" class="cart-price col-sm-6"><h4><?= _xls_currency($model->subtotal); ?></h4></div>
</div>
<?php echo $this->renderPartial('/cart/_carttaxes',array('model'=>$model),true); ?>

<div class="row">
    <div class="col-sm-6 cart-label"><h4><?= Yii::t('cart',"Shipping"); ?></h4></div>
    <div class="col-sm-6 cart-price"><h4><?= _xls_currency($model->shipping_sell); ?></h4></div>
</div>
<div class="row">
    <div class="col-sm-6"><h2><?= Yii::t('cart',"Total"); ?></h2></div>
    <div class="col-sm-6 cart-price"><h2><?= _xls_currency($model->total); ?></h2></div>
</div>
<?php if($model->PromoCode): ?>
    <div class="row">
        <div class="col-sm-4 col-sm-offset-8 promoCode"><?= Yii::t('cart',"Promo Code {code} Applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?></div>
    </div>
<?php endif; ?>