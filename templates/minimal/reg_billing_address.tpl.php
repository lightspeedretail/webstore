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
 * template Billing address block for new registration
 *
 * 
 *
 */

?>

	<fieldset>
		<legend><?php echo _sp('Billing Address') ?><span class="red">*</span></legend>

		<div class="row">
			<div class="ten columns alpha omega">
				<span class="label"><?php echo _sp("Address"); ?></span>
				<?php $this->txtCRBillAddr1->RenderWithError();
					  $this->txtCRBillAddr2->RenderWithError(); ?>
			</div>
		</div>
		<div class="row">
			<div class="ten columns alpha omega">
				<span class="label"><?php echo _sp("City"); ?></span>
				<?php $this->txtCRBillCity->RenderWithError(); ?>
			</div>
		</div>

		<div class="row">
			<div class="ten columns alpha omega">
				<span class="label"><?php echo _sp("Country"); ?></span>
				<?php $this->txtCRBillCountry->RenderWithError(); ?>
			</div>
		</div>

		<div class="row">
			<div class="two columns alpha">
				<span class="label"><?php echo _sp("ST/PR"); ?></span> <span class="red">*</span>
				<?php $this->txtCRBillState->RenderWithError(); ?>
			</div>
			<div class="five columns omega">
				<span class="label"><?php echo _sp("Zip/Postal"); ?></span> <span class="red">*</span>
				<?php $this->txtCRBillZip->RenderWithError(); ?>
			</div>
		</div>


		<div class="row">
			<div class="four columns alpha omega">
				<?php $this->chkSame->Render(); ?>
			</div>
		</div>


	</fieldset>