<?php
/*
 * This file is used in a renderPartial() to display the cart within another view
 * Because our cart is pulled from the component, we can render from anywhere
 *
 * If our controller set intEditMode to be true, then this becomes an edit form to let the user change qty
 */

//This file is also used for receipts which may be independent of 
//our current cart. If we've been passed a Cart object, use that
if (!isset($model)) $model = Yii::app()->shoppingcart;

?>

<div id="genericcart">
    <div class="row-fluid">
        <div class="span3"><span class="cart_header"><?= Yii::t('cart','Description'); ?></span></div>
        <div class="span2 rightitem"><span class="cart_header"><?= Yii::t('cart','Price'); ?></span></div>
        <div class="span1">&nbsp;</div>
        <div class="span1 centeritem"><span class="cart_header"><?= Yii::t('cart','Qty'); ?></span></div>
        <div class="span1">&nbsp;</div>
        <div class="span2 rightitem"><span class="cart_header"><?= Yii::t('cart','Total'); ?></span></div>
	</div>
		<?php foreach($model->cartItems as $item): ?>
			<div class="row-fluid remove-bottom">
			    <div class="span3">
			        <a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a>
			    </div>

			    <div class="span2 cart_price">
				    <?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
			    </div>

			    <div class="span1 centeritem cartdecor">x</div>

			    <div class="span1 centeritem"><span class="cart_qty"><?php
				        if (isset($this->intEditMode) && $this->intEditMode)
						    echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
					        else echo $item->qty;
				    ?></span></div>

			    <div class="span1 centeritem cartdecor">=</div>

			    <div class="span2 cart_price"><?= _xls_currency($item->sell_total) ?></div>
			</div>
		<?php endforeach; ?>



	    <div class="row-fluid remove-bottom">

		    <div class="span2 offset6 cart_price"><span class="cart_label"><?= Yii::t('cart','Subtotal'); ?></span></div>
	        <div class="span2 cart_price"><span id="cartSubtotal"><?= _xls_currency($model->subtotal); ?></span></div>
		</div>
		    <div id="cartTaxes">
			    <?php echo $this->renderPartial('/cart/_carttaxes',array('model'=>$model),true); ?>
		    </div>

		<div class="row-fluid remove-bottom">
		        <div class="span2 offset6 cart_price"><span class="cart_label"><?= Yii::t('cart',"Shipping"); ?></span></div>
		        <div class="span2 cart_price"><span id="cartShipping"><?= _xls_currency($model->shipping_sell); ?></span></div>
		</div>
		<div class="row-fluid remove-bottom">
		        <div class="span2 offset6 cart_price"><?= Yii::t('cart',"Total"); ?></div>
		        <div class="span2 cart_price"><span id="cartTotal"><?= _xls_currency($model->total); ?></span></div>
		</div>
		<?php if($model->PromoCode): ?>
			<div class="row-fluid remove-bottom">
			     <div class="span4 offset6 promoCode"><?= Yii::t('cart',"Promo Code {code} Applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?></div>
			</div>
		<?php endif; ?>

	<?php if (isset($this->intEditMode) && $this->intEditMode): ?>
		<div class="row-fluid">
			<div class="span12 errorMessage">
				<?php echo Yii::t('cart','Note: Change quantity to zero to remove an item from your cart.'); ?>
			</div>
		</div>
	<?php endif; ?>
</div>


