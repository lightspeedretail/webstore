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
 * Deluxe template: Captcha and terms confirmation on CheckOut screen 
 *
 * 
 *
 */

?>

	<fieldset style="display: block; float: left;">
		<legend><?php $this->isLoggedIn() ?  _xt('Update Profile') :  _xt('Complete Registration') ?></legend>

<?	if (_xls_show_captcha('register')) { ?>
		<div class="block margin">
			<dl>
				<dt><label for="Name"><?php $this->lblVerifyImage->Render(); ?></label></dt>
			</dl>
		</div>

		<div class="block margin">
			<dl class="left">
				<dd><?php $this->txtCRVerify->RenderWithError(); ?></dd>
			</dl>
		</div>
<? } ?>


		<div class="left margin clear">
			<dl>
				<dd><?php $this->btnSubmit->Render('CssClass=button rounded') ?></dd>
			</dl>	
		</div>


	</fieldset>
