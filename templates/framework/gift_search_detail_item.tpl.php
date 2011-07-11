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
 * Framework template Wish List (Gift Registry) Wish List viewing My Wish List contents
 * with Buy Now button
 * 
 *
 */


			// fix up the purchase or added qty of the item
			$_ITEM->getPurchaseStatus();
		?>
		<div class="gregistry_row">
			<a href="index.php?product=<?=$_ITEM->Prod->Code?>&ajax=true" class="iframe"><img src="<?= $_ITEM->Prod->SmallImage ?>" class="gregistry_img" /></a>
				<p class="product"><a href="index.php?product=<?=$_ITEM->Prod->Code?>&ajax=true" class="iframe"><?= _xls_truncate($_ITEM->Prod->Name , 50) ?></a>
					<?php if($_ITEM->Prod->ProductSize != ''): ?>
						<br/><?= $_ITEM->Prod->SizeLabel ?> : <?= $_ITEM->Prod->ProductSize; ?>
					<?php endif; ?>
					<?php if($_ITEM->Prod->ProductColor != ''): ?>
						<br/><?= $_ITEM->Prod->ColorLabel ?> : <?= $_ITEM->Prod->ProductColor; ?>
					<?php endif; ?>
				</p>
			<div class="right">
				<p style="margin-right:85px;"><?= _xls_currency($_ITEM->Prod->Price) ?></p>
				<p style="margin-right:95px;"><?= $_ITEM->Qty ?></p>
				<p style="margin-right:20px;"><?= (($_ITEM->PurchasedQty + $_ITEM->AddedQty)>=$_ITEM->Qty)?0:($_ITEM->Qty - ($_ITEM->PurchasedQty + $_ITEM->AddedQty)) ?></p>
				<p style="margin-left:35px;"><?= $_FORM->Purchase_now($_ITEM) ?></p>
			</div>
		</div>	
