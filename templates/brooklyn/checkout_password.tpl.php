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
 * Password entry/change for profile 
 *
 * 
 *
 */
?>
			<fieldset>
				<legend><?php echo _sp('Create a Free Account!') ?></legend>
				<div class="row">
					<?php _xt('To save your information, enter a password here to create an account, or leave blank to check out as a guest.') ?>
				</div>
				<div class="row">
					<div class="five columns alpha">
						<span class="label"><?php echo _sp("Password"); ?></span> <span class="red">*</span>
						<?php $this->PasswordControl->Password1->RenderWithError(); ?>
					</div>
					<div class="five columns alpha omega">
						<span class="label"><?php echo _sp("Confirm Password"); ?></span> <span class="red">*</span>
						<?php $this->PasswordControl->Password2->RenderWithError(); ?>
					</div>
				</div>

				<div class="row">
						<?php $this->PasswordControl->NewsletterSubscribe->Render() ?>
						<span class="label"><?php _xt("Receive emails about special offers") ?></span>
				</div>

		</fieldset>
