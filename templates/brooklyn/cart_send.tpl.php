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
 * template form for Email Cart function
 *
 *
 *
 */

?>
<div id="emailcart" class="container">
	<fieldset>
	    <legend><?php echo _sp('Send Cart to your friend') ?></legend>


			<?php $_CONTROL->errSpan->Render() ?><?php $_CONTROL->objDefaultWaitIcon->Render() ?>
        <br clear="left">


	        <div class="five columns alpha omega">
	            <span class="label"><?php echo _sp("Name of Recipient"); ?></span>
				<?php $_CONTROL->txtToName->RenderWithError() ?>
	        </div>
	        <div class="five columns alpha omega">
                <span class="label"><?php echo _sp("Email Address"); ?></span>
				<?php $_CONTROL->txtToEmail->RenderWithError() ?>
            </div>

		<br clear="left">
            <div class="nine columns alpha omega">
                <span class="label"><?php echo _sp("Message"); ?></span>
				<?php $_CONTROL->txtMsg->RenderWithError() ?>
            </div>

        <br clear="left">
            <div class="five columns alpha omega">
                <span class="label"><?php echo _sp("Your Name"); ?></span> <span class="red">*</span>
				<?php $_CONTROL->txtFromName->RenderWithError() ?>
            </div>
            <div class="five columns alpha omega">
                <span class="label"><?php echo _sp("Your Email Address"); ?></span> <span class="red">*</span>
				<?php $_CONTROL->txtFromEmail->RenderWithError() ?>
            </div>

        <br clear="left">
				<?php $_CONTROL->btnSend->Render('CssClass=button left rounded') ?>
				<?php $_CONTROL->btnCancel->Render('CssClass=button left rounded') ?>


	</fieldset>

</div>