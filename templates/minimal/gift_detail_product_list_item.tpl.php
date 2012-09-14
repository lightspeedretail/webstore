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

<div class="gregistry_row">
	<a href="<?= $_ITEM->Prod->Link ?>"><img src="<?= $_ITEM->Prod->SmallImage ?>" class="gregistry_img"/></a>

	<p class="product"><a href="<?= $_ITEM->Prod->Link ?>"><?= _xls_truncate(
		$_ITEM->Prod->Name, 60, "...\n", true
	); ?></a>
		<?php if ($_ITEM->Prod->ProductSize != ''): ?>
			<br/><?= $_ITEM->Prod->SizeLabel ?> : <?= $_ITEM->Prod->ProductSize
			; ?>
			<?php endif; ?>
		<?php if ($_ITEM->Prod->ProductColor != ''): ?>
			<br/><?= $_ITEM->Prod->ColorLabel ?> : <?= $_ITEM->Prod->ProductColor
			; ?>
			<?php endif; ?>
	</p>

	<div class="right">
		<p class="qty" style="margin: 0 65px 0 0;"><?= $_FORM->QtyColumn_Render($_ITEM); ?></p>
		<!-- 				<p class="status"><?= $_FORM->PurchaseColumn_Render($_ITEM); ?></p>  -->
		<p class="delete" style="margin: 0 15px 0 0;"><a href="#" <?php $_FORM->pxyGiftItemDelete->RenderAsEvents(
			$_ITEM->Rowid
		); ?>><img src="<?= templateNamed('css/images/btn_remove.png') ?>" alt="<?php _xt('Delete') ?>"/></a></p>
	</div>
</div>
