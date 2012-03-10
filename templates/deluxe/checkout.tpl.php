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
 * Deluxe template: CheckOut screen (calls other elements)
 *
 * 
 *
 */

?>

<div id="checkout">
			
<?php $this->errSpan->Render() ?>

<?php $this->pnlWait->Render('CssClass=center'); ?>

<br style="clear: both;"/>

<?php $this->pnlLoginRegister->Render(); ?>

<?php $this->pnlCustomer->Render(); ?>

<?php $this->pnlBillingAdde->Render(); ?>

<div style="display: block; float: left; clear: right;"><?php $this->pnlShippingAdde->Render(); ?></div>

<br style="clear: both;"/>
<?php if(isset($this->pnlPromoCode) && ($this->pnlPromoCode->Visible)): ?>

<?php $this->pnlPromoCode->Render() ?>
<?php endif; ?>

<?php $this->pnlShipping->Render(); ?>

<br style="clear: both;"/>

<?php $this->pnlCart->Render(); ?>
		  
<br style="clear: both;"/>		  

<?php $this->pnlPayment->Render(); ?>		  			
			
			
<br style="clear: both;"/>

			
<?php $this->pnlVerify->Render(); ?>		  			

<?php $this->LoadActionProxy->Render(); ?>
</div>			
