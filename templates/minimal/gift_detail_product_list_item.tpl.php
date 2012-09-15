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
 * template Wish List (Gift Registry) Wish List product list
 *
 *
 *
 */

?>

<div class="row">
	<div class="four columns alpha">
		<a href="<?php echo $_ITEM->Prod->Link; ?>"><?=  _xls_truncate($_ITEM->Prod->Name, 65, "...\n", true); ?></a>
	</div>

	<div class="two columns cart_price"><span class="cart_qty"><?= $_FORM->QtyColumn_Render($_ITEM); ?></span></div>

	<div class="two columns"><?= $_FORM->PurchaseColumn_Render($_ITEM); ?></div>

	<div class="two columns centeritem omega"><a href="#" <?php $_FORM->pxyGiftItemDelete->RenderAsEvents($_ITEM->Rowid); ?>>
		<img src="<?= templateNamed('css/images/btn_remove.png') ?>" alt="<?php _xt('Delete') ?>"/></a></div>

</div>

