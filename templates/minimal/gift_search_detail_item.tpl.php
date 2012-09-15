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
 * template Wish List (Gift Registry) Wish List viewing My Wish List contents
 * with Buy Now button
 *
 *
 */


// fix up the purchase or added qty of the item
$_ITEM->getPurchaseStatus();
?>


	<div class="row">
		<div class="six columns alpha omega">
			<a href="<?php echo $_ITEM->Prod->Link; ?>"><?=  _xls_truncate($_ITEM->Prod->Name, 65, "...\n", true); ?></a>
		</div>


		<div class="one column alpha omega"><?= _xls_currency($_ITEM->Prod->Price); ?></div>

		<div class="one columns alpha omega center"><?= $_ITEM->Qty ?></div>

		<div class="one columns alpha omega center"><?= (($_ITEM->PurchasedQty + $_ITEM->AddedQty) >= $_ITEM->Qty) ? 0
			: ($_ITEM->Qty - ($_ITEM->PurchasedQty + $_ITEM->AddedQty)) ?></div>


		<div class="three columns alpha omega"><?= $_FORM->Purchase_now($_ITEM) ?></div>

	</div>

