<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * template Mini-cart, shopping cart displayed while browsing
 *
 * 
 *
 */

$cart = Cart::GetCart();

$items = $cart->GetCartItemArray(); 

?>
		

	<div id="shoppingcarttop" class="rounded-top">
		<span class="title"><?=  _sp("Shopping Cart"); ?></span><img src="<?= templateNamed("css/images/shoppingcart.png"); ?>" class="carticon">

		<div class="minicart_itemlist">
			<?php if($cart->Count > 0):  ?>
					<?php foreach($items as $item): ?>

					<?php  if(!$item->Prod) continue; ?>

							<div class="minicart_item">
								<span class="minicart_image">
									<a href="<?= $item->Prod->Link; ?>">
										<img src="<?= $item->Prod->MiniImage ?>"
									     height="<?php echo _xls_get_conf('MINI_IMAGE_HEIGHT'); ?>px"
									     width="<?php echo _xls_get_conf('MINI_IMAGE_WIDTH'); ?>px"/>
									</a>
								</span>
								<span class="minicart_desc">
									<a href="<?= $item->Prod->Link; ?>">
										<?= _xls_truncate($item->Description , 24) ?>
										<!--<?php if($item->Prod->ProductSize != ''): ?>
										<br/><?= $item->Prod->SizeLabel ?> : <?= $item->Prod->ProductSize; ?>
										<?php endif; ?>
										<?php if($item->Prod->ProductColor != ''): ?>
											<br/><?= $item->Prod->ColorLabel ?> : <?= $item->Prod->ProductColor; ?>
										<?php endif; ?>-->
										<br>
										<span class="minicart_qty"><?php _xt('Quantity'); ?>: <?= $item->Qty ?></span>
									</a>
								</span>
								<span class="minicart_price">
									<?= _xls_currency($item->SellTotalTaxIncIfSet) ?>
								</span>
							</div>


					<?php endforeach; ?>
			<?php else: ?>
						<div class="emptymessage"><?php _xt($this->strEmptyCartMessage); ?></div>
			<?php endif; ?>
		</div>

	</div>

	<div id="shoppingcartbottom">
		<p class="subtotal"><?php _xt("Subtotal"); ?></p>
		<p class="total"><?= _xls_currency($cart->Subtotal) ?></p>

		<?php if($cart->Count > 0):  ?>
			<a href="cart/pg" class="review rounded"><?php _xt("Edit Cart"); ?></a>
			<a href="<? echo _xls_site_url("checkout/pg");?>" class="gocheckout rounded"><?php _xt("Check Out"); ?></a>

		<?php endif; ?>

	</div>


