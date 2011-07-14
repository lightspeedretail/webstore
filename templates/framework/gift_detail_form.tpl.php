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
 * Framework template Wish List (Gift Registry) Create New Wish List form (details)
 * 
 * 
 *
 */

?>


	<div class="registry rounded">
		<div class="registry_header">
				<?php _xt('Create New Wish List'); ?>
		</div>

		<fieldset style="border: none;">
		<div class="left" class="gregistry_form">
			<dt><label class="grl"><?php _xt('Name your Wish List') ?></label></dt>
			<dd><?php $this->txtGRName->RenderWithError('CssClass=grinput'); ?></dd>
			
			<dt style="clear:both;"><label class="grl"><?php _xt('Choose a password') ?></label></dt>
			<dd><?php $this->txtGRPassword->RenderWithError('CssClass=grinput'); ?></dd>
			
			<dt style="clear:both;"><label class="grl"><?php _xt('Confirm the Password') ?></label></dt>
			<dd><?php $this->txtGRConfPassword->RenderWithError('CssClass=grinput'); ?></dd>
			
			<dt style="clear:both;"><label class="grl"><?php _xt('When should your Wish List expire? (mm/dd/yyyy)') ?></label></dt>
			<dd><?php $this->txtGRDate->RenderWithError('CssClass=grinput' ); ?></dd>

			<dt style="clear:both;"><label class="grl"><?php _xt('Where should the items ship to?') ?></label></dt>
			<dd><?php $this->txtGRShipOption->RenderWithError('CssClass=grinput'); ?></dd>
		</div>
		
		<div class="left" style="width: 350px; text-align: left; padding: 15px 0 0 0;">
			<dt><label class="left"><?php _xt('Create a description for your Wish List:') ?></label></dt>
			<dd><?php $this->txtGRHtmlContent->RenderWithError(); ?></dd>			
		</div>
		<div class="left">
			<?php $this->btnGRSave->Render('CssClass=button rounded'); ?>
			<?php $this->btnGRCancel->Render('CssClass=button rounded'); ?>
		</div>
		</fieldset>
	</div>	
