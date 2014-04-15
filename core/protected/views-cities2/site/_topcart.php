<div id="shoppingcartbottom">


	<div class="cart_label">
        <?= CHtml::link(Yii::t('checkout','{n} item in cart|{n} items in cart',
	        Yii::app()->shoppingcart->cartQty
	        ),array('cart/index')) ?>
	</div>

	<div style="clear: both;"></div>

	<div class="cart_price">
		<?= CHtml::link(Yii::t('checkout','{subtotal}',
			array('{subtotal}'=>_xls_currency(Yii::app()->shoppingcart->subtotal)
			)),array('cart/index')) ?>
	</div>


	<div class="carticon">&nbsp;</div>



</div>
