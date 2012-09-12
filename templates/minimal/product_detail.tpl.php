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
 * template Product View
 *
 *
 *
 */

?>


	<div id="product_details">
		<div class="five columns alpha">
			<div class="row">
				<?php $this->pnlImgHolder->Render(); ?>
			</div>
			<div class="row">
				<?php foreach ($this->arrAdditionalProdImages as $img): ?>
				<?php if ($img && ($img instanceof Images)): ?>
					<a href="#" <?php $this->pxyEnlarge->RenderAsEvents($img->Rowid) ?>><img
						src="<?= Images::GetImageLink($img->Rowid, ImagesType::preview); ?>" alt=""/></a>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if (_xls_get_conf('PRODUCT_ENLARGE_SHOW_LIGHTBOX', 1)): ?>
				<br/><a href="#" <?php $this->pxyEnlarge->RenderAsEvents($this->prod->ImageId) ?>>
					<?php _xt('Preview') ?></a>
				<?php endif; ?>
			</div>
			<div class="row">
				<?php $this->pnlSharingTools->Render(); ?>
			</div>
		</div>

		<div class="seven columns omega">
			<div class="row">
				<h1><?php $this->lblTitle->Render(); ?></h1>

				<?php if (_xls_get_conf('SHOW_TEMPLATE_CODE', 1)): ?>
					<h3><?= $this->prod->Code ?></h3>
				<?php endif; ?>

				<div class="price"><?php $this->lblPrice->Render(); ?></div>
				<div class="price_reg"><?php $this->lblOriginalPrice->Render(); ?></div>

				<?php if (_xls_get_conf('INVENTORY_DISPLAY')): ?>
					<div class="stock"><?php $this->lblStock->Render(); ?></div>
				<?php endif; ?>
			</div>

			<div class="row">
				<?php $this->lstSize->Render(); ?>
				<?php $this->lstColor->Render(); ?>

				<?php if (!_xls_get_conf('DISABLE_CART', false)): ?>

					<div class="addcart">
						<a href="#" <?php $this->misc_components['add_to_cart']->RenderAsEvents($this->prod->Code) ?>><?php _xt("Add to Cart"); ?></a>
					</div>

					<?php if (_xls_get_conf('ENABLE_GIFT_REGISTRY', 0)): ?>
						<div class="wishlist">
							<a href="#" <?php  $this->misc_components['show_gift_registry']->RenderAsEvents('show') ?>><?php _xt("Add to Wish List"); ?></a>
						</div>
					<?php endif; ?>

				<?php endif; ?>

				<?php $this->giftRegistryPnl->Render(); ?>


				<?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
					<?php $this->txtQty->Render(); ?>
				<?php endif; ?>

			</div>

			<div class="row">
				<div class="description">
					<h2>Product Description</h2>
					<?php $this->lblDescription->Render(); ?>
				</div>
			</div>


			<?php if (count($this->arrAutoAddProducts) > 0): ?>
				<div class="row">
					<fieldset>
						<legend><?php _xt("Recommended Products"); ?></legend>

						<?php foreach ($this->arrAutoAddProducts as $pqty): ?>
						<?php $prod = $pqty['prod'];
						$qty = $pqty['qty']; ?>
						<?php if (!_xls_get_conf('DISABLE_CART', false)): ?>
							<div class="checkbox"><?php $this->AutoAddCheckBox($prod, $qty) ?></div>
							<?php endif; ?>
						<a href="<?= $prod->Link; ?>">
							<p><?= $prod->Name ?></p>
							<img src="<?= $prod->SmallImage ?>" alt="<?php $prod->Code ?>" class=""/>
						</a>
						<br style="clear: both"/>
						<?php endforeach; ?>
					</fieldset>

				</div>
			<?php endif; ?>

		</div>
	</div>


	<?php if (count($this->arrRelatedProducts) > 0): ?>
	<?php $this->sldRelated->Render(); ?>
	<?php endif; ?>


<?php $this->dxImage->Render(); ?>
