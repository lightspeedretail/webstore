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
 * Basic template: Customer registration form (upper right Register button)
 *
 * 
 *
 */

?>

<div id="customer_registration">
			<?php $this->errSpan->Render() ?>
					
	<fieldset>
		<legend><?php _xt('Account Information') ?></legend>

		<div class="left margin">
			<dl>
				<dt><label for="Name"><span class="red">*</span> <?php _xt("First Name"); ?></label></dt>
				<dd><?php $this->txtCRFName->RenderWithError() ?></dd>
			</dl>
		</div>

		<div class="left margin">
			<dl class="left">
				<dt><label for="Name"><span class="red">*</span> <?php _xt("Last Name"); ?></label></dt>
				<dd><?php $this->txtCRLName->RenderWithError() ?></dd>
			</dl>
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Company"><?php _xt("Company"); ?></label></dt>
				<dd><?php $this->txtCRCompany->Render() ?></dd>
			</dl><br />
		</div>

		<div class="left margin clear">
			<dl>
				<dt><span class="red">*</span> <label for="Phone"><?php _xt("Phone"); ?> &nbsp; <?php $this->txtCRMPhoneType->Render('CssClass=customer_reg_input_label_field') ?></label></dt>
				<dd><?php $this->txtCRMPhone->RenderWithError() ?></dd>
			</dl>
</div>
	</fieldset>	
			
			
			
			



<?php  $this->pnlBillingAdde->Render(); ?>

<div style="display: block; float: left;"><?php  $this->pnlShippingAdde->Render(); ?></div>


<?php $this->objDefaultWaitIcon->Render() ?>	




<div style="clear: both;"></div>

    <fieldset>
    <legend><?php _xt("Account Information") ?></legend>
    
		<div class="left margin">
	        <dl>
	        	<dt><label for="Email"><span class="red">*</span> <?php _xt("Email"); ?></label></dt>
	            <dd><?php $this->txtCREmail->RenderWithError(true) ?></dd>
	        </dl>
		</div>

		<div class="left margin">
			<dl class="left">
	        	<dt><label for="Confirm Email"><span class="red">*</span> <?php _xt("Confirm Email"); ?></label></dt>
	            <dd><?php $this->txtCRConfEmail->RenderWithError(true) ?></dd>
			</dl>
		</div>

 		<div style="clear: both;"></div>  
		<div class="left margin clear">
	        <dl>
	        	<dt><label for="Password"><span class="red">*</span><?php _xt("Password"); ?></label></dt>
	            <dd><?php $this->txtCRPass->RenderWithError() ?></dd>
	        </dl>
		</div>

		<div class="left margin">
			<dl class="left">
	        	<dt><label for="cPassword"><span class="red">*</span> <?php _xt("Confirm Password"); ?></label></dt>
	            <dd><?php $this->txtCRConfPass->RenderWithError() ?></dd>
			</dl>
		</div><br />



			<div class="cbhtml">
        	<dl>
            <dd>
               <br/> <?php $this->chkHtmlEmail->Render() ?><label for="htmlemail" class="opt"><?php _xt("Receive HTML Email (Untick this box if you want to receive Text-Only email)") ?></label>
            </dd>
            </dl>
        	</div>

        <!-- <div class="">
        	<dl>
            <dd>
                <?php $this->chkNewsletter->Render() ?><label for="newsletter" class="opt"><?php _xt("Subscribe to our Newsletter") ?></label>
            </dd>
            </dl>
        	</div>-->
    </fieldset>

<div style="clear: both;"></div>

    <fieldset>
    <legend><?php _xt("Confirmation") ?></legend>
    
    	<div class="left margin clear">
	    <?php $this->lblVerifyImage->Render() ?>
	    </div>
		<div class="left margin clear">
	        <dl>
	        	<dt><label for="Verify"><span class="red">*</span> <?php _xt("Enter the letters from above") ?></label></dt>
	            <dd><?php $this->txtCRVerify->RenderWithError(true) ?></dd>
	        </dl>
		</div>

		<div class="left margin clear"><br />
			<?php $this->btnSave->Render('CssClass=button rounded') ?><br />
		</div>

    </fieldset>
			
</div>
