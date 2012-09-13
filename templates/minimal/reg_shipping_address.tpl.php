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
 * Shipping address block for new registration
 *
 * 
 *
 */

?>

<fieldset>
	<legend><?php echo _sp('Shipping Address') ?><span class="red">*</span></legend>

	<?php  if(isset($this->blnShowShippingNames)):   ?>
	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("First Name"); ?></span> <span class="red">*</span>
			<?php $this->ShippingContactControl->FirstName->RenderWithError(); ?>
		</div>
	</div>
	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Last Name"); ?></span> <span class="red">*</span>
			<?php $this->ShippingContactControl->LastName->RenderWithError(); ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Address"); ?></span>
			<?php   $this->ShippingContactControl->Street1->RenderWithError();
			$this->ShippingContactControl->Street2->RenderWithError(); ?>
		</div>
	</div>
	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("City"); ?></span>
			<?php $this->ShippingContactControl->City->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Country"); ?></span>
			<?php $this->ShippingContactControl->Country->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="two columns alpha">
			<span class="label"><?php echo _sp("State/Prov"); ?></span> <span class="red">*</span>
			<?php $this->ShippingContactControl->State->RenderWithError(); ?>
		</div>
		<div class="three columns omega">
			<span class="label"><?php echo _sp("Zip/Postal"); ?></span> <span class="red">*</span>
			<?php $this->ShippingContactControl->Zip->RenderWithError(); ?>
		</div>
	</div>


</fieldset>

