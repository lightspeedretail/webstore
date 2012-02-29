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
 * Deluxe template: Account Information form displayed on CheckOut screen 
 *
 * 
 *
 */

?>

		<fieldset>
		<legend><?php _xt('Account Information') ?></legend>

		<div class="left margin">
			<dl>
				<dt><label for="Name"><span class="red">*</span> <?php _xt("First Name"); ?></label></dt>
				<dd><?php $this->txtCRFName->RenderWithError(); ?></dd>
			</dl>
		</div>

		<div class="left margin">
			<dl class="left">
				<dt><label for="Name"><span class="red">*</span> <?php _xt("Last Name"); ?></label></dt>
				<dd><?php $this->txtCRLName->RenderWithError(); ?></dd>
			</dl>
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Company"><?php _xt("Company"); ?></label></dt>
				<dd><?php $this->txtCRCompany->Render(); ?></dd>
			</dl><br />
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Phone"><span class="red">*</span> <?php _xt("Phone"); ?></label></dt>
				<dd><?php $this->txtCRMPhone->RenderWithError() ?></dd>
			</dl>	
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Email"><span class="red">*</span> <?php _xt("Email"); ?></label></dt>
				<dd><?php $this->txtCREmail->RenderWithError() ?></dd>
			</dl>	
		</div>
		</fieldset>	
			
