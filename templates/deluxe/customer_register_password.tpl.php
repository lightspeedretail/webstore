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
	<fieldset style="display: block; float: left; width:300px;">
	<legend><span class="red">*</span> <?php $this->isLoggedIn() ?  _xt('Change Password') :  _xt('Set Password') ?></legend>

	<div class="left margin clear">
	        <dl>
	        	<dt><label for="Password"><span class="red">*</span> <?php _xt("Password"); ?></label></dt>
	            <dd><?php $this->txtCRPass->RenderWithError() ?></dd>
	        </dl>
		</div>

		<div class="left margin">
			<dl class="left">
	        	<dt><label for="cPassword"><span class="red">*</span> <?php _xt("Confirm Password"); ?></label></dt>
	            <dd><?php $this->txtCRConfPass->RenderWithError() ?></dd>
			</dl>
		</div><br />
		
		</fieldset>
