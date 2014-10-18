<div class="webstore-modal webstore-modal-overlay webstore-modal-cart-confirm">
	<section id="addcart">
		<article class="twocolumn">

			<div class="column item-confirm">
				<header>
					<h1>
						<?php
						echo Yii::t('cart','Item added to your cart');
						?>
					</h1>
				</header>
				<div class="content">
					<?php echo CHtml::image(Images::GetLink($this->intImageID,ImagesType::addtocartmodal)); ?>
					<div class="product-data">
					<h3 id="product-title"><?php echo _xls_truncate($this->objCartItem->description,50); ?></h3>
					<p class="sku"><?php echo $this->objCartItem->code; ?></p>
					<p class="quantity"><?php echo Yii::t('cart','Quantity').': '.$this->objCartItem->qty; ?></p>
					<p class="price"><?php echo _xls_currency($this->objCartItem->sell_total); ?></p>
					<?php
					echo CHtml::htmlButton(
						Yii::t(
							'cart',
							'Change'
						),
						array('class'=>'webstore-change-item')
					);
					?>
					</div>
				</div>
			</div>
			<div class="column cart-summary">
				<header>
					<h1 id="add-to-cart-cart-summary-label"><?php echo Yii::t('cart','Cart Summary'); ?>
						<small id="add-to-cart-items-count"><?php echo $this->intItemCount.' '.$this->strItems; ?></small>
					</h1>
				</header>
				<div class="content">
					<?php $this->widget('ext.wsshippingestimator.WsShippingEstimatorTooltip'); ?>

					<table class="totals">
						<tbody>
							<tr>
								<th colspan="2"><?php echo Yii::t('cart','Order Subtotal'); ?></th>
								<td id="addtocart-ordersubtotal" class="money"><?php echo _xls_currency(Yii::app()->shoppingcart->subtotal) ?></td>
							</tr>
							<?php
								$this->widget(
									'ext.wsshippingestimator.WsShippingEstimator',
									array('updateShippingOptions' => true)
								);
							?>

							<tr id="PromoCodeLine" class="<?php echo Yii::app()->shoppingcart->promoCode ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
								<td colspan="2"><?php echo Yii::t('cart','Promo & Discounts') ?></td>
								<td id="addtocart-promodiscount" class="money"><?php echo Yii::app()->shoppingcart->totalDiscountFormatted; ?></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="2"><?php echo Yii::t('cart','Total'); ?></th>
								<td id="addtocart-subtotal" class="wsshippingestimator-total-estimate money"><?php echo _xls_currency($this->objCart->total); ?></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<form class="webstore-promo-form promo">
					<?php
						echo CHtml::tag(
							'div',
							array(
								'id' => CHtml::activeId($this->objCart,'promoCode') . '_em_',
								'class' => 'form-error',
								'style' => 'display: none'
							),
							'<p>&nbsp;</p>'
						);?>
					<div style="position:relative;">
						<?php
							echo CHtml::textField(
								CHtml::activeId($this->objCart,'promoCode'),
								(Yii::app()->shoppingcart->promoCode !== null ? Yii::app()->shoppingcart->promoCode : ''),
								array(
									'placeholder' => Yii::t('cart','Enter Promo Code'),
									'onkeypress' => 'return wsaddtocartmodal.ajaxTogglePromoCodeEnterKey(event, ' .
										json_encode(CHtml::activeId($this->objCart,'promoCode')) .
										');',
									'readonly' => Yii::app()->shoppingcart->promoCode !== null
								)
							);

							echo CHtml::htmlButton (
								Yii::app()->shoppingcart->promoCode !== null ? Yii::t('cart', 'Remove') : Yii::t('cart', 'Apply'),
								array(
									'type' => 'button',
									'class' => 'inset promocode-apply' . (Yii::app()->shoppingcart->promoCode !== null ? ' promocode-applied' : ''),
									'onclick' => 'wsaddtocartmodal.ajaxTogglePromoCode(' .
										json_encode(CHtml::activeId($this->objCart, 'promoCode')) .
										');'
								)
							);
						?>
					</div>
				</form>
				<footer class="cf">
					<div class="button" onclick="window.location.href='<?php echo Yii::app()->controller->createUrl('/checkout'); ?>'">
						<div><?php echo CHtml::link(Yii::t('cart','Checkout'), array('/checkout'), array('class' => 'checkout')); ?></div>
					</div>
					<button id="continue-shopping-btn" class="continue-shopping exit">
						<?php echo Yii::t('cart','Continue Shopping'); ?>
					</button>
				</footer>
			</div>
		</article>
		<?php if ($this->arrObjRelated): ?>
			<aside class="related-products">
				<h4><?php echo Yii::t('cart','Related Products'); ?></h4>
				<ul>
					<?php
						foreach ($this->arrObjRelated as $obj):
							echo CHtml::link(
								CHtml::tag(
									'li',
									array(),
									CHtml::image(Images::GetLink($obj->image_id,ImagesType::slider)).
									CHtml::tag('h3',array(),$obj->Title).
									CHtml::tag('p',array('class'=>'price'),$obj->Price)
								),
								$obj->Link
							);
						endforeach;
					?>
				</ul>
			</aside>
		<?php endif; ?>
		<?php echo CHtml::htmlButton(Yii::t('cart','Close'),array('class'=>'webstore-modal-close')); ?>
	</section>
</div>
