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
 * template Line Item for product listed in shopping cart (CheckOut screen)
 *
 *
 *
 */

?>
	<br clear="left">
	<div class="four columns alpha">
		<?=  _xls_truncate($_ITEM->Description, 65, "...\n", true); ?>
	</div>

	<div class="two columns cart_price"><?= ($_ITEM->Discounted) ? sprintf("<strike>%s</strike> ", _xls_currency($_ITEM->SellBase))._xls_currency($_ITEM->SellDiscount)
		: _xls_currency($_ITEM->Sell);  ?></div>

	<div class="one columns centeritem cartdecor">x</div>

	<div class="one columns centeritem"><span class="cart_qty"><?= $_ITEM->Qty ?></span></div>

	<div class="one columns centeritem cartdecor">=</div>

	<div class="two columns omega cart_price"><?= _xls_currency($_ITEM->SellTotal) ?></div>


