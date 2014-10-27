
	<div id="cart_link" class="checkoutlink shoppingcartholder">
		<?php echo CHtml::link(Yii::t('Shopping Cart','Shopping Cart ({n})',Yii::app()->shoppingcart->totalItemCount), array('cart/index')) ?>
	</div>

