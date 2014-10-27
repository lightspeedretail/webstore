<div id="shoppingcart" class="shoppingcarthidden shoppingcartpopup">
	<div class="shoppingcartarrow"></div>
	<div id="shoppingcarttop">
		<div class="shoppingcartitems">
			<?php if(!empty(Yii::app()->shoppingcart)):
				foreach (Yii::app()->shoppingcart->cartItems as $item): ?>
					<div id="cartline<?=$item->id?>" class="minicart_item">
						<span class="minicart_image">
							<a href="<?=$item['link']?>">
								<img src="<?=$item->product->MiniImage?>"  />
							</a>
						</span>
						<span class="span6 minicart_desc">
							<a href="<?=$item['link']?>"><?=$item['description']?>
								<br>
								<span class="minicart_qty">
									<?php echo Yii::t('cart','Qty'); ?>: <span id="qty<?=$item['id']?>"><?=$item['qty']?></span> &nbsp;&nbsp;
                                </span>
							</a>
						</span>
						<span class="span3 minicart_price">
							<span id="sell_total<?=$item['id']?>"><?=_xls_currency($item['sell_total'])?></span>
						</span>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<div id="cartline0" class="minicart_item hidden">
	            <span class="minicart_image">
					<a href="">
						<img id="product_img0" src="" />
					</a>
				</span>
				<span class="span6 minicart_desc">
					<a id="product_link0" href=""><span id="product_title0"></span>
						<br>
						<span class="minicart_qty"><?php echo Yii::t('cart','Qty'); ?>: <span id="qty0"></span> &nbsp;&nbsp;
                        </span>
					</a>
				</span>
				<span class="span3 minicart_price">
					<span id="sell_total0"></span>
				</span>
			</div>

			<?php if(empty(Yii::app()->shoppingcart)): ?>
				<div id="cartempty">
					<div class="emptymessage"><?php echo Yii::t('cart','Your cart is empty'); ?></div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<div id="shoppingcartbottom">
		<div class="cart_label span7"><span class="subtotallabel"><?php echo Yii::t('cart','SubTotal'); ?></span></div>
		<div class="cart_price span5"><span id="subtotal"><?= _xls_currency(Yii::app()->shoppingcart->subtotal) ?></span>&nbsp;&nbsp;</div>
	</div>


	<div id="shoppingcartcheckout" onclick="window.location.href='<?php echo Yii::app()->createUrl('cart/checkout') ?>'">
		<div class="checkoutlink"><?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')) ?></div>
		<div class="checkoutarrow"><?php echo CHtml::image(Yii::app()->theme->baseUrl."/css/images/checkoutarrow.png"); ?></div>
	</div>

	<div id="shoppingcarteditcart" onclick="window.location.href='<?php echo Yii::app()->createUrl('/cart') ?>'">
		<div class="editlink"><?php echo CHtml::link(Yii::t('cart','Edit Cart'),array('/cart')) ?></div>
	</div>
</div>		
