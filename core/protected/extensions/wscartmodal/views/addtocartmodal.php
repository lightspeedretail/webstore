<div class="webstore-modal webstore-modal-overlay webstore-modal-cart-confirm">
	<section id="addcart">
		<article class="twocolumn">

			<div class="column item-confirm">
				<header>
					<h1><?= Yii::t('cart', 'Item added to your cart'); ?></h1>
				</header>
				<div class="content">
					<?php echo CHtml::image($this->objCartItem->Prod->AddToCartImage); ?>
					<div class="product-data">
					<h3 id="product-title"><?php echo _xls_truncate($this->objCartItem->description, 50); ?></h3>
					<p id="addtocart-sku" class="sku"><?php echo $this->objCartItem->code; ?></p>
					<p id ="addtocart-quantity" class="quantity"><?php echo Yii::t('cart', 'Quantity').': '.$this->objCartItem->qty; ?></p>
					<p id="addtocart-price" class="price"><?php echo _xls_currency($this->objCartItem->sell_total); ?></p>
					<?php
						echo CHtml::htmlButton(
							Yii::t(
								'cart',
								'Change'
							),
							array('class' => 'webstore-change-item', 'id' => 'change-item-btn', 'data-editcarturl' => Yii::app()->createUrl('editcart'))
						);
					?>
					</div>
				</div>
			</div>
			<div class="column cart-summary">
				<header>
					<h1 id="add-to-cart-cart-summary-label"><?php echo Yii::t('cart', 'Cart Summary'); ?>
						<small id="add-to-cart-items-count"><?php echo $this->intItemCount . ' ' . $this->strItems; ?></small>
					</h1>
				</header>
				<div class="pricechange">
				</div>
				<div class="content">
					<?php $this->widget('ext.wsshippingestimator.WsShippingEstimatorTooltip'); ?>

					<table class="totals">
						<tbody>
							<tr>
								<th colspan="2"><?php echo Yii::t('cart', 'Subtotal'); ?></th>
								<td id="addtocart-ordersubtotal" class="money cart-subtotal"><?= _xls_currency(Yii::app()->shoppingcart->subtotal) ?></td>
							</tr>
							<tr id="PromoCodeLine" class="<?php echo Yii::app()->shoppingcart->displayPromoLine() ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
								<td colspan="2"><?php echo Yii::t('cart', 'Promos & Discounts') ?></td>
								<td id="addtocart-promodiscount" class="money promo-code-str"><?php echo Yii::app()->shoppingcart->totalDiscountFormatted; ?></td>
							</tr>
							<?php
								$this->widget(
									'ext.wsshippingestimator.WsShippingEstimator',
									array('updateShippingOptions' => true)
								);
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="2"><?php echo Yii::t('cart', 'Total'); ?></th>
								<td id="addtocart-subtotal" class="wsshippingestimator-total-estimate total-estimate money">
									<?= _xls_currency($this->objCart->total); ?>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<form class="webstore-promo-form promo">
					<?php
						echo CHtml::tag(
							'div',
							array(
								'id' => CHtml::activeId($this->objCart, 'promoCode') . '_em_',
								'class' => 'form-error',
								'style' => 'display: none'
							),
							'<p>&nbsp;</p>'
						);?>
					<div style="position:relative;">
						<?php
							$this->controller->renderPartial(
								'ext.wscartmodal.views._promocodeinput',
								array('modelId' => $this->objCart, 'updateCartTotals' => false, 'reloadPageOnSuccess' => false)
							);
						?>
					</div>
				</form>
				<footer class="cf">
					<div class="button checkout" onclick="window.location.href='<?php echo Yii::app()->createUrl('/checkout/index'); ?>'">
							<?php
								echo CHtml::link(
									Yii::t(
										'cart',
										'Checkout'
									),
									Yii::app()->createUrl('/checkout/index'),
									array(
										'id' => 'checkout-btn'
									)
								);
							?>
					</div>
					<button id="continue-shopping-btn" class="continue-shopping exit">
						<?= Yii::t('cart', 'Continue Shopping'); ?>
					</button>
				</footer>
			</div>
		</article>
		<?php if ($this->arrObjRelated): ?>
			<aside class="related-products">
				<h4><?php echo Yii::t('cart', 'Related Products'); ?></h4>
				<ul>
					<?php
						foreach ($this->arrObjRelated as $obj):
							echo CHtml::link(
								CHtml::tag(
									'li',
									array(),
									CHtml::image($obj->SliderImage) .
									CHtml::tag('h3', array(), $obj->Title).
									CHtml::tag('p', array('class' => 'price'), $obj->Price)
								),
								$obj->Link
							);
						endforeach;
					?>
				</ul>
			</aside>
		<?php endif; ?>
		<?php echo CHtml::htmlButton(Yii::t('cart', 'Close and continue shopping'), array('class' => 'webstore-modal-close')); ?>
	</section>
</div>
