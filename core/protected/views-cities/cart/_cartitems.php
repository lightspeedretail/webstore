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

	<?php if($model->PromoCode): ?>
		<div class="row-fluid remove-bottom">
			<div class="promoCode">
				<?= Yii::t('cart',"Promo Code {code} applied",array('{code}'=>"<strong>".$model->PromoCode."</strong>")); ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="row-fluid">
		<table class="table" title="Shopping Cart Table">
			<thead>
			<tr>
				<th class="cart_header desc"><?= Yii::t('cart','Description'); ?></th>
				<th class="cart_header price"><?= Yii::t('cart','Price'); ?></th>
				<th class="cart_header mlt"></th>
				<th class="cart_header cart_qty"><?= Yii::t('cart','Qty'); ?></th>
				<th class="cart_header eql"></th>
				<th class="cart_header cart_total"><?= Yii::t('cart','Total'); ?></th>
			</tr>
			</thead>
			<tbody>

			<?php foreach($model->cartItems as $item): ?>
				<tr>
					<td>
						<a href="<?php echo $item->Link; ?>"><?=  _xls_truncate($item->description, 65, "...\n", true); ?></a>
					</td>
					<td>
						<?= ($item->discount) ? sprintf("<strike>%s</strike> ", _xls_currency($item->sell_base))._xls_currency($item->sell_discount) : _xls_currency($item->sell);  ?>
					</td>
					<td class="mlt">
						x
					</td>
					<td>
						<span class="cart_qty">
							<?php
							if (isset($this->intEditMode) && $this->intEditMode)
								echo CHtml::textField(CHtml::activeId($item,'qty')."_".$item->id,$item->qty,array('class'=>'cart_qty_box'));
							else echo $item->qty;
							?>
						</span>
					</td>
					<td class="cart_decor eql">
						=
					</td>
					<td class="cart_price">
						<?= _xls_currency($item->sell_total) ?>
					</td>
				</tr>
			<?php endforeach; ?>

			<tr>
				<td colspan="6"></td>
			</tr>

			<tr>
				<td colspan="3"></td>
				<td class="visible1-mobile"><?= Yii::t('cart','Subtotal'); ?></td>
				<td class="hidden1-mobile"><?= Yii::t('cart','Subtotal'); ?></td>
				<td class="cart_price"><span id="cartSubtotal"><?= _xls_currency($model->subtotal); ?></span></td>
			</tr>

			<tr>
				<td colspan="3"></td>
				<td class="visible1-mobile"><?= Yii::t('cart',"Shipping"); ?></td>
				<td class="hidden1-mobile"><?= Yii::t('cart',"Shipping"); ?></td>
				<td class="cart_price"><span id="cartShipping"><?= _xls_currency($model->shipping_sell); ?></span></td>
			</tr>

			<tr>
				<td colspan="3"></td>
				<td colspan="3" style="padding: 0">
					<table id="cartTaxes">
						<?php $this->renderPartial('/cart/_carttaxes',array('model'=>$model)); ?>
					</table>
				</td>
			</tr>


			<tr>
				<td colspan="3"></td>
				<td class="visible1-mobile"><?= Yii::t('cart',"Total"); ?></td>
				<td class="hidden1-mobile"><?= Yii::t('cart',"Total"); ?></td>
				<td class="cart_price"><span id="cartTotal"><?= _xls_currency($model->total); ?></span></td>
			</tr>
			</tbody>
		</table>
	</div>
</div>



