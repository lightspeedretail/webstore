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
 * template Wish List (Gift Registry) Wish List details list (description, expiry, shipping)
 *
 *
 *
 */
?>

	<div id="wishlistdisplay" class="twelve columns alpha omega">

		<div class="row">
			<div class="eight columns alpha">
				<h1><?php $this->misc_components['lblGRName']->Render(); ?></h1>
			</div>
			<div class="four columns alpha omega darkbutton">
				<a href="#" <?php $this->pxyGREdit->RenderAsEvents(); ?>><?php _xt('Edit Settings') ?></a>
			</div>
		</div>

		<div class="row">
			<div class="two columns alpha"><span class="label"><?php _xt('Expires') ?>:</span></div>
				<div class="four columns"><?php $this->misc_components['lblGRExpDate']->Render(); ?></div>
			<div class="two columns alpha"><span class="label"><?php _xt('Shipping option') ?>:</span></div>
			<div class="four columns"><?php $this->misc_components['lblGRShipOption']->Render(); ?></div><br clear="left">

		</div>

		<div class="row">
			<div class="ten column alpha omega"><span class="label"><?php _xt('Description') ?>:</span></div>
			<div class="ten column offset-by-one"><?php $this->misc_components['lblGRHTML']->Render(); ?></div>
		</div>

	</div>

