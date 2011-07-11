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
 * Basic template: Wish List (Gift Registry) Wish List viewing other's Wish Lists
 * form for search by email address
 * 
 *
 */

?>
	<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?php _xt('Search'); ?></p>
		</div>
		<p style="margin: 15px auto; text-align: center;">Email Address: <?php $this->txtEmail->RenderWithError(); ?> <?php $this->btnSearch->Render('CssClass= button rounded search'); ?>	</p>			

	</div>

	
	
<?php if($this->dtrGiftRegistry->Visible): ?>	
	<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?php _xt('Wish List'); ?></p>
			<div class="right">
				<p style="margin: 0 115px 0 0;"><?php _xt('Who'); ?></p>
				<p style="margin: 0 91px 0 0;"><?php _xt('Expiry'); ?></p>
			</div>
		</div>
				
		<?php $this->dtrGiftRegistry->Render(); ?>

	</div>
<?php endif; ?>
<br style="clear: both;"/>
