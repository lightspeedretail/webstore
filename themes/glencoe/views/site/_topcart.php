<button class="btncart" data-toggle="dropdown">
    <img src="/themes/glencoe/css/images/carticon.png">CART <span class="lightgrey"> <?= Yii::app()->shoppingcart->totalItemCount ?>  &nbsp;item(s)</span>  <span class="caret"></span>
</button>

<ul class="dropdown-menu pull-right top_cart_style" role="menu">
	<li class="menu_toggle"></li>
	<li style="padding-left: 10px; padding-top: 5px;">
		<?php
		if (count(Yii::app()->shoppingcart->cartItems)==0)
			echo Yii::t('cart','Your cart is empty');
		else { ?>
			<table >
				<?php $model = Yii::app()->shoppingcart;
				foreach ($model->cartItems as $item) { ?>
					<tr>
						<td class="cartdropdown" id="cartqty-dropdown"><?= $item->qty ?></td>
						<td class="cartdropdown" id="cartdesc-dropdown">
							<a href="<?php echo $item->Link; ?>">
								<?= _xls_truncate($item->description, 65, "...\n", true) ?>
							</a>
						</td>
						<td class="cartdropdown" id="cartsell-dropdown"><?= _xls_currency($item->sell) ?></td>
					</tr>
				<?php } ?>
				<tr>
					<td>&nbsp;</td>
					<td class="cartdropdown" id="cartlabel-dropdown">
						<?= Yii::t('cart','Subtotal'); ?>
					</td>
					<td class="cartdropdown" id="cartsubtotal-dropdown"><?= _xls_currency($model->subtotal); ?></td>
				</tr>
			</table>
		<?php } ?>
	</li>
	<li class="divider"></li>
	<li id="editlink-dropdown" class="editcart_btn"><?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('/cart')) ?></li>
	<li id="checkoutlink-dropdown" class="checkout_btn"><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')); ?></li>
</ul>

