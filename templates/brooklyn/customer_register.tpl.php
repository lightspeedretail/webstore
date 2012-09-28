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
 * Deluxe template: Customer registration form (upper right Register button)
 *
 * 
 *
 */

	//We purposely use the "checkout" ID here as well as checkout.tpl.php since it's basically the same form
?>

<div id="checkout">

	<div class="row"><?php $this->errSpan->Render() ?></div>

	<div id="customercontact" class="ten columns alpha omega"><?php $this->pnlCustomer->Render(); ?></div>

	<div id="createaccount" class="ten columns alpha omega"><?php $this->pnlPassword->Render(); ?></div>


	<div class="row">
		<div id="billingaddress" class="six columns alpha"><?php $this->pnlBillingAdde->Render(); ?></div>
		<div id="shippingaddress" class="six columns alpha omega"><?php $this->pnlShippingAdde->Render(); ?></div>
	</div>


	<div class="row"><?php $this->pnlVerify->Render(); ?></div>


</div>

