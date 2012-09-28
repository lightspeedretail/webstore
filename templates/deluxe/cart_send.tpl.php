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
 * Deluxe template: form for Email Cart function
 *
 * 
 *
 */

?>			
			<div class="modal_reg_box_wrap                                            ">
				<div class="modal_reg_box_body">
					<div class="modal_reg_box_header"><?php _xt("Send Cart to your friend") ?></div>
				
					<div class="modal_reg_msg"><?php $_CONTROL->errSpan->Render() ?><?php $_CONTROL->objDefaultWaitIcon->Render() ?></div>
					
					<div class="modal_reg_input_wrap">
						<span class="modal_reg_input_label">
							<?php _xt("Name of Recipient") ?> *
						</span>
						
						<?php $_CONTROL->txtToName->RenderWithError(true) ?>
					</div>
					
					<div class="modal_reg_input_wrap">
						<span class="modal_reg_input_label">
							<?php _xt("Email Address")?>*
						</span>
						
						<?php $_CONTROL->txtToEmail->RenderWithError(true) ?>
					</div>
					
					<div class="modal_reg_input_wrap">
						<span class="modal_reg_input_label">
							<?php _xt("Message") ?> *
						</span>
						
						<?php $_CONTROL->txtMsg->RenderWithError() ?></div>
					
					<div class="modal_reg_input_wrap">
						<span class="modal_reg_input_label">
							<?php _xt("Your Name") ?>*
						</span>
						
						<?php $_CONTROL->txtFromName->RenderWithError() ?></div>
					
					<div class="modal_reg_input_wrap">
						<span class="modal_reg_input_label">
							<?php _xt("Your Email Address") ?>*
						</span>
						
						<?php $_CONTROL->txtFromEmail->RenderWithError() ?></div>
	
					
					<div class="model_reg_buttons" style="position: relative; left: 175px;">
						<?php $_CONTROL->btnSend->Render('CssClass=button left rounded') ?> 
						<?php $_CONTROL->btnCancel->Render('CssClass=button left rounded') ?></div>
				</div>
			</div>
