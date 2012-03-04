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
 * Deluxe template: Billing address block for new registration 
 *
 * 
 *
 */

?>
	<fieldset style="display: block; float: left; width:300px;">
	<legend><span class="red">*</span> <?php _xt("Billing Address") ?></legend>

	<div class="block margin">
	<dl>
		<dt><label for="Name"><?php _xt("Address") ?></label></dt>

		<dd><?php $this->txtCRBillAddr1->RenderWithError() ?></dd>
		<dd style="margin-top: 5px;"><?php $this->txtCRBillAddr2->RenderWithError() ?></dd>
	</dl>
	</div>
	
	<div class="left margin clear">
	<dl>
	<dt><label for="City" class="city"><?php _xt("City") ?></label></dt>
	
	<dd><?php $this->txtCRBillCity->RenderWithError() ?></dd>
	</dl>
	</div>

	<div class="block margin clear">
	<dl>
	<dt><label for="Country"><?php _xt("Country") ?></label></dt>
	
	<dd><?php $this->txtCRBillCountry->RenderWithError() ?></dd>
	</dl>
	</div><br />

	<div class="block margin clear">
	<dl>
	<dt><label for="State"><?php _xt("State/Province") ?></label></dt>
	
	<dd><?php $this->txtCRBillState->RenderWithError() ?></dd>
	</dl>
	</div><br />

	<div class="left margin clear">
	<dl>
	<dt><label for="Zip" class="zip"><?php _xt("Zip/Postal Code") ?></label></dt>
	
	<dd><?php $this->txtCRBillZip->RenderWithError() ?></dd>
	</dl>
	</div>
	
	<?php if(isset($this->chkSame) && ($this->chkSame->Visible)): ?>
	<dl>
	<dd style="margin-top: 20px;"><?php $this->chkSame->Render() ?></dd>
	</dl>
	</fieldset>
	<?php endif; ?>
