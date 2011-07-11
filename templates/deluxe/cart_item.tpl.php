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
 * Deluxe template: Line Item for product listed in shopping cart (Edit Cart)
 *
 * 
 *
 */

?>
		
		  
		  <div class="cart_row">
				<a href="<?= $_ITEM->Prod->Link ?>">
				<img src="<?= $_ITEM->Prod->SmallImage ?>" />
				<p class="product">
				<?= _xls_truncate($_ITEM->Prod->Name, 63) ?>
					<!--  <br/>
					<?php if($_ITEM->Prod->ProductSize != ''): ?>
						<?= $_ITEM->Prod->SizeLabel ?> : <?= $_ITEM->Prod->ProductSize; ?>
					<?php endif; ?>
					<?php if($_ITEM->Prod->ProductColor != ''): ?>
						<?= $_ITEM->Prod->ColorLabel ?> : <?= $_ITEM->Prod->ProductColor; ?>
					<?php endif; ?>
					 -->
				</p>
				<p><?= $_ITEM->Prod->Code ?></p>
				</a>
			
			<div class="receipt_row">
                <p class="price">
                <?= ($_ITEM->Discounted)?_xls_currency($_ITEM->SellDiscount) . sprintf("<br/><strike>%s</strike>" , _xls_currency($_ITEM->SellBase)     ):_xls_currency($_ITEM->Sell);  ?>
                </p>
				<p class="qty"><?= $_FORM->qtyBox($_ITEM) ?></p>
				<p class="total"><?= _xls_currency($_ITEM->SellTotal) ?></p>
				<a href="#" <?= $_FORM->minusIcon($_ITEM) ?>><div class="minus_icon"></div></a>
			</div>
		</div>

