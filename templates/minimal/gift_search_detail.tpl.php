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
 * template Wish List (Gift Registry) Wish List viewing other's Wish List contents
 * with Buy Now button
 *
 *
 */


$this->dlgGiftQty->Render(); ?>

	<div id="orderdisplay" class="twelve column alpha omega">

		<? if (!$this->logTrack): ?>


			<div class="row">
				<p style="padding: 50px;"><span class="label"><?php _xt("Password") ?></span> &nbsp; <?php $this->txtGListPassword->RenderWithError() ?> &nbsp; <?php $this->btnGetIn->Render() ?></p>
			</div>


			<?php else: ?>

			<div class="row">
				<div class="six columns alpha omega">
					<span class="label"><?= $this->objGiftDetail->RegistryName; ?></span>
				</div>
			</div>

			<div class="row">
				<div class="six columns alpha omega">
					<?= stripslashes($this->objGiftDetail->HtmlContent); ?>
				</div>
			</div>

			<div class="row">
				Note: Purchases must be made from this screen to properly deduct from the Wish List. Choosing Add To Cart from the product details page will not show as purchased on this list.
			</div>

			<div class="row">
				<div class="six columns alpha omega">
					<span class="label"><?php _xt("Item") ?></span>
				</div>


				<div class="one column alpha omega"><span class="label"><?php _xt("Price") ?></span></div>

				<div class="one columns alpha omega center"><span class="label"><?php _xt("Req") ?></span></div>

				<div class="one columns alpha omega center"><span class="label"><?php _xt("Remain") ?></span></div>


				<div class="three columns alpha omega"><span class="label"></span></div>
			</div>

			<?php $this->dtrGiftList->Render() ?>


			<?php  endif; ?>

	
	</div>
	
	
			

			

