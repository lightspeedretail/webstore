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
 * template View order header details (id, date, status, notes)
 * calls other views
 * Called from /order-track/pg?getuid=xxxxxxxxxx
 *
 */


if($this->order): ?>
	<div id="orderdisplay" class="twelve column alpha omega">
		<h1 class="center"><?php $this->lblOrderMsg->Render(); ?></h1>

		<div class="row">
			<div class="two columns alpha"><span class="label"><?php _xt('Order ID') ?>:</span></div>
				<div class="four columns"><?php $this->lblIdStr->Render() ?></div>
			<div class="two columns alpha"><span class="label"><?php _xt('Date') ?>:</span></div>
				<div class="four columns"><?php $this->lblOrderDate->Render() ?></div><br clear="left">
			<div class="two columns alpha"><span class="label"><?php _xt('Status') ?>:</span></div>
				<div class="four columns"><?php $this->lblOrderStatus->Render() ?></div>
			<div class="two columns alpha"><span class="label"><?php _xt('Payment') ?>:</span></div>
				<div class="four columns"><?php $this->lblPaymentNotes->Render() ?></div><br clear="left">
			<div class="two columns alpha"><span class="label"><?php _xt('Shipping') ?>:</span></div>
				<div class="four columns"><?php $this->lblShippingNotes->Render() ?></div>
			<div class="two columns alpha"><span class="label"><?php _xt('Authorization') ?>:</span></div>
				<div class="four columns"><?php $this->lblOrderPaymentData->Render() ?></div><br clear="left">
		</div>

		<div class="row">
			<div class="ten column alpha omega"><span class="label"><?php _xt('Notes') ?>:</span></div>
			<div class="ten column offset-by-one"><?= nl2br($this->order->PrintedNotes) ?></div>
		</div>

		<div class="row">
			<?php $this->orderViewItemsPnl->Render(); ?>
		</div>


		<?php $this->lblConversionCode->Render(); ?>

	</div>


<?php endif; ?>