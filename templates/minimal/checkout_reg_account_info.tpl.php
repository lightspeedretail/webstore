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
 * template Account Information form displayed on CheckOut screen
 *
 * 
 *
 */

?>


		<fieldset>
		<legend><?php echo _sp('Customer Contact') ?></legend>

			<div class="row">
				<div class="five columns alpha">
					<span class="label"><?php echo _sp("First Name"); ?></span> <span class="red">*</span>
					<?php $this->BillingContactControl->FirstName->RenderWithError(); ?>
				</div>
				<div class="five columns omega">
					<span class="label"><?php echo _sp("Last Name"); ?></span> <span class="red">*</span>
					<?php $this->BillingContactControl->LastName->RenderWithError(); ?>
				</div>
			</div>

			<div class="row">
				<div class="ten columns alpha omega">
					<span class="label"><?php echo _sp("Company"); ?></span>
					<?php $this->BillingContactControl->Company->RenderWithError(); ?>
				</div>
			</div>

			<div class="row">
				<div class="five columns alpha omega">
					<span class="label"><?php echo _sp("Phone"); ?></span> <span class="red">*</span>
					<?php $this->BillingContactControl->Phone->RenderWithError(); ?>
				</div>
			</div>

			<div class="row">
				<div class="five columns alpha">
					<span class="label"><?php echo _sp("Email"); ?></span> <span class="red">*</span>
					<?php $this->BillingContactControl->Email->RenderWithError(); ?>
				</div>
				<?php if (!$this->isLoggedIn()) { ?>
				<div class="five columns omega">
					<span class="label"><?php echo _sp("Email (Confirm)"); ?></span> <span class="red">*</span>
					<?php $this->BillingContactControl->EmailConfirm->RenderWithError(); ?>
				</div>
				<? } ?>
			</div>

		</fieldset>	
			
