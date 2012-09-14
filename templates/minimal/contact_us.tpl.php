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
 * template Contact Us email form
 * Also displays Contact Us text from Web Admin panel
 * 
 *
 */

?>


<fieldset>
	<legend><?php echo _sp('Contact').' '._xls_get_conf('STORE_NAME'); ?></legend>

	<?php $this->lblError->Render(); ?>

	<div class="row">
		<div class="five columns alpha >
			<span class="label"><?php echo _sp("Name"); ?></span>
			<?php   $this->txtName->RenderWithError(); ?>
		</div>
		<div class="five columns omega">
			<span class="label"><?php echo _sp("Email"); ?></span>
			<?php $this->txtEmail->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Phone"); ?></span>
			<?php $this->txtPhone->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Subject"); ?></span>
			<?php $this->txtSubject->RenderWithError(); ?>
		</div>
	</div>

	<div class="row">
		<div class="five columns alpha omega">
			<span class="label"><?php echo _sp("Message"); ?></span>
			<?php $this->txtMsg->RenderWithError('Width=315' , 'Height=150'); ?>
		</div>
	</div>


	<?	if (_xls_show_captcha('contactus')) { ?>
		<div class="row">
			<div class="five columns alpha omega">
				<?php $this->lblVerifyImage->Render(); ?>
				<?php $this->txtCRVerify->RenderWithError(); ?>
			</div>
		</div>
	<? } ?>

	<div class="row">
		<?php $this->btnSubmit->Render('CssClass=button rounded'); ?>
	</div>

</fieldset>



			
