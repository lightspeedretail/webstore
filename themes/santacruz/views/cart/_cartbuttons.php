
<div class="row">
    <div class="col-sm-7">
        <p class="lead"><?php echo Yii::t('cart','Note: Change quantity to zero to remove an item from your cart.'); ?></p>
    </div>
</div>

<div class="row">
    <div id="empty-button" class="col-xs-4 col-sm-2">
        <?php echo CHtml::ajaxButton(
            Yii::t('cart', 'Empty Cart'),
            array('cart/clearcart'),
            array('data'=>array(),
                'type'=>'POST',
                'dataType'=>'json',
                'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
            ),array('confirm'=>Yii::t('cart',"Are you sure you want to erase your cart items?"),
                'class'=>'btn btn-link'
            )); ?>
    </div>
    <div class="col-xs-4 col-sm-offset-3 col-sm-3">
        <?php echo CHtml::ajaxButton(
            Yii::t('cart', 'Update Cart'),
            array('cart/updatecart'),
            array('data'=>'js:$("#ShoppingCart").serialize()',
                'type'=>'POST',
                'dataType'=>'json',
                'success' => 'js:function(data){
	                    if (data.action=="alert") {
	                      alert(data.errormsg);
						} else if (data.action=="success") {
							 location.reload();
						}}'
            ),
            array('class'=>'btn btn-block btn-default'));
        ?>
    </div>
</div>

<div class="row">
<div class="col-xs-4 col-sm-2 sharecart">
    <?php echo CHtml::htmlButton(
        Yii::t('cart', 'Share Cart')."&nbsp&nbsp<span class='glyphicon glyphicon-share icon-white'></span>",
        array('class'=>'btn btn-link',
            'onClick'=>'js:jQuery($("#CartShare")).dialog("open");return false;',

        )
    ); ?>
</div>
<div class="col-sm-offset-3 col-sm-3">
    <?= CHtml::htmlButton(
        Yii::t('cart','Continue Shopping')."&nbsp&nbsp<span class='glyphicon glyphicon-chevron-right icon-white'></span>",
        array('id'=>'cartcontinue','class'=>'btn btn-block btn-default',
            'onclick'=>'window.location.href=\''. $this->returnUrl.'\'')
    );
    ?>
</div>
<div id="checkout-btn" class="col-sm-offset-1 col-sm-3">
    <?= CHtml::tag('div',array(
            'id'=>'cart-checkout',
            'class'=>'btn btn-block btn-primary checkoutlink',
            'onclick'=>'window.location.href=\''. $this->CreateUrl("cart/checkout").'\''),
        Yii::t('cart','Checkout'));
    ?>
</div>
</div>